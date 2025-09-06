<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_SUPER_ADMIN = 'super-admin';
    const ROLE_ADMIN = 'admin';
    const ROLE_EDITOR = 'editor';
    const ROLE_USER = 'user';
    const ROLE_COMPANY = 'company';
    const ROLE_CANDIDATE = 'candidate';
    
    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class)->withTimestamps();
    }

    /**
     * The permissions that belong to the user.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class)->withTimestamps();
    }

    /**
     * Check if user has a role
     *
     * @param string|array $role
     * @return bool
     */
    public function hasRole($role): bool
    {
        if (is_string($role)) {
            return $this->roles->contains('slug', $role);
        }

        if (is_array($role)) {
            return $this->roles->whereIn('slug', $role)->count() > 0;
        }

        return false;
    }

    /**
     * Check if user has a permission
     *
     * @param string|array $permission
     * @return bool
     */
    public function hasPermission($permission): bool
    {
        if (is_string($permission)) {
            // Check direct permissions
            if ($this->permissions->contains('slug', $permission)) {
                return true;
            }
            
            // Check role permissions
            foreach ($this->roles as $role) {
                if ($role->permissions->contains('slug', $permission)) {
                    return true;
                }
            }
            
            return false;
        }
        
        if (is_array($permission)) {
            foreach ($permission as $perm) {
                if ($this->hasPermission($perm)) {
                    return true;
                }
            }
            return false;
        }
        
        return false;
    }
    
    /**
     * Check if user has any of the given roles
     *
     * @param array|string $roles
     * @return bool
     */
    public function hasAnyRole($roles): bool
    {
        if (is_string($roles)) {
            return $this->roles->contains('slug', $roles);
        }
        
        if (is_array($roles)) {
            foreach ($roles as $role) {
                if ($this->hasRole($role)) {
                    return true;
                }
            }
        }
        
        return false;
    }
    
    /**
     * Assign roles to the user
     *
     * @param array $roleIds
     * @return void
     */
    public function assignRoles(array $roleIds): void
    {
        $this->roles()->sync($roleIds);
    }
    
    /**
     * Assign a role to the user
     *
     * @param string|Role $role
     * @return void
     */
    public function assignRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        $this->roles()->syncWithoutDetach([$role->id]);
    }
    
    /**
     * Remove a role from the user
     *
     * @param string|Role $role
     * @return void
     */
    public function removeRole($role): void
    {
        if (is_string($role)) {
            $role = Role::where('slug', $role)->firstOrFail();
        }

        $this->roles()->detach($role->id);
    }
    
    /**
     * Get all permissions for the user
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAllPermissions()
    {
        $permissions = $this->permissions;
        
        foreach ($this->roles as $role) {
            $permissions = $permissions->merge($role->permissions);
        }
        
        return $permissions->unique('id');
    }
    
    /**
     * Check if user has all of the given permissions
     *
     * @param array $permissions
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
    {
        $userPermissions = $this->getAllPermissions()->pluck('slug')->toArray();
        
        foreach ($permissions as $permission) {
            if (!in_array($permission, $userPermissions)) {
                return false;
            }
        }
        
        return true;
    }
    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}") ?: $this->name;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'avatar_url',
        'last_login_at',
        'email_verified_at',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'role' => self::ROLE_CANDIDATE,
        'is_active' => true,
    ];

    /**
     * The relationships that should always be loaded.
     *
     * @var array
     */
    protected $with = ['company'];

    /**
     * Get the company associated with the user.
     */
    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    /**
     * Check if the user is a company.
     */
    public function isCompany(): bool
    {
        return $this->role === self::ROLE_COMPANY;
    }

    /**
     * Check if the user is an admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if the user is a candidate.
     */
    public function isCandidate(): bool
    {
        return $this->role === self::ROLE_CANDIDATE;
    }

    /**
     * Check if the user is active.
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    /**
     * Update the last login timestamp.
     */
    public function updateLastLogin(): bool
    {
        return $this->forceFill([
            'last_login_at' => now(),
        ])->save();
    }
}
