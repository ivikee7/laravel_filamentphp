<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleHasPermission extends Model
{
    protected $table = 'role_has_permissions';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'permission_id',
        'role_id',
    ];

    public function permissions(): BelongsTo
    {
        return $this->belongsTo(Permission::class, 'permission_id', 'id');
    }
}
