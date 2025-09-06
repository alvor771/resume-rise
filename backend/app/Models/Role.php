<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_protected',
        'level',
    ];

    protected $casts = [
        'is_protected' => 'boolean',
        'level' => 'integer',
    ];

    /**
     * The users that belong to the role.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withTimestamps();
    }

    /**
     * The permissions that belong to the role.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->permissions()->where('slug', $permission)->exists();
    }

    /**
     * Check if role has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->permissions()->whereIn('slug', $permissions)->exists();
    }

    /**
     * Check if role has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        $count = $this->permissions()->whereIn('slug', $permissions)->count();
        return $count === count(array_unique($permissions));
    }

    /**
     * Assign permissions to the role
     */
    public function assignPermissions(array $permissionIds): void
    {
        $this->permissions()->syncWithoutDetaching($permissionIds);
    }

    /**
     * Sync permissions for the role (removes all existing permissions)
     */
    public function syncPermissions(array $permissionIds): void
    {
        $this->permissions()->sync($permissionIds);
    }

    /**
     * Revoke permissions from the role
     */
    public function revokePermissions(array $permissionIds): void
    {
        $this->permissions()->detach($permissionIds);
    }

    /**
     * Get all permission names for the role
     */
    public function getPermissionNames(): Collection
    {
        return $this->permissions->pluck('name');
    }

    /**
     * Get all permission slugs for the role
     */
    public function getPermissionSlugs(): Collection
    {
        return $this->permissions->pluck('slug');
    }

    /**
     * Scope a query to only include roles that have the given permission.
     */
    public function scopeWithPermission($query, $permission)
    {
        return $query->whereHas('permissions', function ($q) use ($permission) {
            $q->where('slug', $permission);
        });
    }

    /**
     * Scope a query to only include roles that are assignable
     */
    public function scopeAssignable($query)
    {
        return $query->where('is_protected', false);
    }

    /**
     * Check if the role is protected (cannot be deleted/modified)
     */
    public function isProtected(): bool
    {
        return (bool) $this->is_protected;
    }

    /**
     * Get the role level (for hierarchy)
     */
    public function getLevel(): int
    {
        return (int) $this->level;
    }
}
