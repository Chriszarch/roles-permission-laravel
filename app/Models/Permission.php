<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Permission extends Model
{
    protected $table = 'permissions';

    protected $fillable = [
        'module_id',
        'action',
        'permission_key',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ==============================================
    // RELATIONSHIPS
    // ==============================================

    /**
     * The roles that belong to the permission.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_permissions',
            'permission_id',
            'role_id'
        )->withTimestamps();
    }

    /**
     * The users that have this permission directly (higher priority).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_permissions',
            'permission_id',
            'user_id'
        )->withTimestamps();
    }

    public function module(): BelongsTo
    {
        return $this->belongsTo(Module::class);
    }

    public function getFullName(): string
    {
        return $this->module->name.' - '.$this->action;
    }

    public function is_active(): bool
    {
        return $this->roles()->where('is_active', true)->exists();
    }
}
