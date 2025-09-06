<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'group',
        'is_protected',
        'is_assignable',
    ];

    protected $casts = [
        'is_protected' => 'boolean',
        'is_assignable' => 'boolean',
    ];

    /**
     * The roles that belong to the permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * The users that belong to the permission.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'permission_user')
            ->withTimestamps();
    }

    /**
     * Scope a query to only include permissions in the given group.
     */
    public function scopeInGroup($query, string $group)
    {
        return $query->where('group', $group);
    }

    /**
     * Scope a query to only include assignable permissions.
     */
    public function scopeAssignable($query)
    {
        return $query->where('is_assignable', true);
    }

    /**
     * Scope a query to only include protected permissions.
     */
    public function scopeProtected($query)
    {
        return $query->where('is_protected', true);
    }

    /**
     * Get all permission groups.
     */
    public static function getGroups(): Collection
    {
        return static::select('group')
            ->distinct()
            ->orderBy('group')
            ->pluck('group');
    }

    /**
     * Check if the permission is protected.
     */
    public function isProtected(): bool
    {
        return (bool) $this->is_protected;
    }

    /**
     * Check if the permission is assignable to users.
     */
    public function isAssignable(): bool
    {
        return (bool) $this->is_assignable;
    }

    /**
     * Get the permission's full slug (group.slug).
     */
    public function getFullSlugAttribute(): string
    {
        return $this->group ? "{$this->group}.{$this->slug}" : $this->slug;
    }

    /**
     * Find a permission by its full slug (group.slug).
     */
    public static function findByFullSlug(string $fullSlug): ?self
    {
        $parts = explode('.', $fullSlug, 2);
        
        if (count($parts) === 2) {
            return static::where('group', $parts[0])
                ->where('slug', $parts[1])
                ->first();
        }
        
        return static::where('slug', $fullSlug)->first();
    }
}
