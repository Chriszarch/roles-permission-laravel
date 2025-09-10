<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * Role Model
 * 
 * Represents system roles (Admin, User, Manager, etc.)
 * Each role can have multiple permissions and be assigned to multiple users
 * 
 * @property int $id
 * @property string $name
 * @property string|null $description
 * @property bool $is_active
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 */
class Role extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     * (Optional - Laravel infers 'roles' from class name)
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     * These fields can be filled using create() or fill() methods
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast to specific types.
     * Laravel will automatically convert these when accessing
     */
    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Default values for attributes
     */
    protected $attributes = [
        'is_active' => true,
    ];

    // ==============================================
    // RELATIONSHIPS
    // ==============================================

    /**
     * Users that have this role (Many-to-Many)
     * 
     * A role can be assigned to multiple users
     * Uses pivot table: user_roles
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,           // Related model
            'user_roles',          // Pivot table name
            'role_id',             // Foreign key on pivot table for this model
            'user_id'              // Foreign key on pivot table for related model
        )->withTimestamps();       // Include created_at/updated_at in pivot
    }

    /**
     * Permissions assigned to this role (Many-to-Many)
     * 
     * A role can have multiple permissions
     * Uses pivot table: role_permissions
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,     // Related model
            'role_permissions',    // Pivot table name
            'role_id',            // Foreign key on pivot table for this model
            'permission_id'       // Foreign key on pivot table for related model
        )->withTimestamps();      // Include created_at/updated_at in pivot
    }

    // ==============================================
    // QUERY SCOPES
    // ==============================================

    /**
     * Scope to get only active roles
     * Usage: Role::active()->get()
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get roles by name
     * Usage: Role::byName('admin')->first()
     */
    public function scopeByName($query, string $name)
    {
        return $query->where('name', $name);
    }

    // ==============================================
    // HELPER METHODS
    // ==============================================

    /**
     * Check if role has a specific permission
     * 
     * @param string $permissionKey The permission key to check
     * @return bool
     */
    public function hasPermission(string $permissionKey): bool
    {
        return $this->permissions()
            ->where('permission_key', $permissionKey)
            ->exists();
    }

    /**
     * Assign a permission to this role
     * 
     * @param Permission|int $permission
     * @return void
     */
    public function givePermission($permission): void
    {
        if (is_int($permission)) {
            $this->permissions()->attach($permission);
        } else {
            $this->permissions()->attach($permission->id);
        }
    }

    /**
     * Remove a permission from this role
     * 
     * @param Permission|int $permission
     * @return void
     */
    public function revokePermission($permission): void
    {
        if (is_int($permission)) {
            $this->permissions()->detach($permission);
        } else {
            $this->permissions()->detach($permission->id);
        }
    }

    /**
     * Get all permission keys for this role
     * 
     * @return array
     */
    public function getPermissionKeys(): array
    {
        return $this->permissions()
            ->pluck('permission_key')
            ->toArray();
    }
}
