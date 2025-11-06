<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    // ==============================================
    // RELATIONSHIPS
    // ==============================================

    /**
     * Get the roles for the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'user_roles',
            'user_id',
            'role_id'
        )->withTimestamps();
    }

    /**
     * Get the direct permissions for the user (higher priority).
     */
    public function directPermissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'user_permissions',
            'user_id',
            'permission_id'
        )->withTimestamps();
    }

    /**
     * Get the QR codes for the user.
     */
    public function qrCodes(): HasMany
    {
        return $this->hasMany(QrCode::class);
    }

    // ==============================================
    // MÉTODOS BÁSICOS PARA ROLES Y PERMISOS
    // ==============================================

    /**
     * Verificar si el usuario tiene un rol específico
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Verificar si el usuario tiene un permiso específico.
     * Jerarquía: Permisos directos > Permisos por rol
     *
     * NOTA: Para autorización usa Gate::allows() o $this->authorize()
     * que automáticamente pasa por Gate::before() con la jerarquía correcta.
     */
    public function hasPermission(string $permissionKey): bool
    {
        // 1. PRIORIDAD MÁXIMA: Verificar permisos directos del usuario
        if ($this->hasDirectPermission($permissionKey)) {
            return true;
        }

        // 2. SEGUNDA PRIORIDAD: Verificar permisos a través de roles
        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permissionKey) {
                $query->where('permission_key', $permissionKey);
            })
            ->exists();
    }

    /**
     * Obtener todos los permisos del usuario (directos + por roles).
     * Los permisos directos prevalecen en caso de conflicto.
     */
    public function getAllPermissions(): array
    {
        // Permisos directos
        $directPermissions = $this->directPermissions()
            ->pluck('permission_key')
            ->toArray();

        // Permisos por roles
        $rolePermissions = $this->roles()
            ->with('permissions')
            ->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('permission_key')
            ->toArray();

        // Combinar y eliminar duplicados (los directos ya están primero)
        return collect($directPermissions)
            ->merge($rolePermissions)
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Verificar si el usuario tiene un permiso directo (sin considerar roles)
     */
    public function hasDirectPermission(string $permissionKey): bool
    {
        return $this->directPermissions()
            ->where('permission_key', $permissionKey)
            ->exists();
    }

    /**
     * Asignar un permiso directo al usuario
     */
    public function giveDirectPermission(Permission|int $permission): void
    {
        $permissionId = $permission instanceof Permission ? $permission->id : $permission;

        $this->directPermissions()->syncWithoutDetaching([$permissionId]);
    }

    /**
     * Revocar un permiso directo del usuario
     */
    public function revokeDirectPermission(Permission|int $permission): void
    {
        $permissionId = $permission instanceof Permission ? $permission->id : $permission;

        $this->directPermissions()->detach($permissionId);
    }
}
