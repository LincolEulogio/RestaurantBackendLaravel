<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'permissions'];

    protected $casts = [
        'permissions' => 'array',
    ];

    protected $attributes = [
        'permissions' => '{}',
    ];

    /**
     * Get all available permission keys
     */
    public static function availablePermissions(): array
    {
        return [
            'dashboard' => 'Acceso al panel principal y métricas',
            'orders' => 'Gestión de pedidos y órdenes',
            'kitchen' => 'Acceso a pantalla de cocina (KDS)',
            'tables' => 'Gestión de mesas y distribución',
            'reservations' => 'Gestión de reservas',
            'menu' => 'Edición de productos y categorías',
            'inventory' => 'Control de stock y materias primas',
            'reports' => 'Análisis y reportes financieros',
            'billing' => 'Caja y facturación',
            'blogs' => 'Gestión de publicaciones del blog',
            'settings' => 'Ajustes del sistema y administración',
        ];
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return ($this->permissions[$permission] ?? false) === true;
    }

    /**
     * Grant a permission to this role
     */
    public function grantPermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        $permissions[$permission] = true;
        $this->permissions = $permissions;
    }

    /**
     * Revoke a permission from this role
     */
    public function revokePermission(string $permission): void
    {
        $permissions = $this->permissions ?? [];
        $permissions[$permission] = false;
        $this->permissions = $permissions;
    }

    /**
     * Get count of users with this role
     */
    public function getUserCountAttribute(): int
    {
        return \App\Models\User::where('role', $this->slug)->count();
    }

    /**
     * Get formatted permissions for display
     */
    public function getFormattedPermissionsAttribute(): array
    {
        $formatted = [];
        $available = self::availablePermissions();
        
        foreach ($available as $key => $description) {
            $formatted[$key] = [
                'name' => ucfirst($key),
                'description' => $description,
                'enabled' => $this->hasPermission($key),
            ];
        }
        
        return $formatted;
    }
}
