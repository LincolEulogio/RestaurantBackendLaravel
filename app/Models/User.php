<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role model for this user
     */
    public function roleModel()
    {
        return $this->hasOne(Role::class, 'slug', 'role');
    }

    /**
     * Get role permissions
     */
    public function getRolePermissions(): array
    {
        $roleModel = Role::where('slug', $this->role)->first();

        return $roleModel ? ($roleModel->permissions ?? []) : [];
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        // Superadmin has all permissions
        if ($this->role === 'superadmin') {
            return true;
        }

        $permissions = $this->getRolePermissions();

        // Check if permission exists and is truthy (handles true, "1", 1, etc.)
        if (!isset($permissions[$permission])) {
            return false;
        }

        $value = $permissions[$permission];
        
        // Handle different types: boolean, string, integer
        if (is_bool($value)) {
            return $value;
        }
        
        // Convert string/int to boolean
        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }

    /**
     * Check if user has any of the given permissions
     */
    public function hasAnyPermission(array $permissions): bool
    {
        // Superadmin has all permissions
        if ($this->role === 'superadmin') {
            return true;
        }

        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has all of the given permissions
     */
    public function hasAllPermissions(array $permissions): bool
    {
        // Superadmin has all permissions
        if ($this->role === 'superadmin') {
            return true;
        }

        foreach ($permissions as $permission) {
            if (! $this->hasPermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $role): bool
    {
        return $this->role === $role;
    }

    /**
     * Check if user is admin or superadmin
     */
    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'superadmin']);
    }

    /**
     * Check if user is superadmin
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === 'superadmin';
    }
}
