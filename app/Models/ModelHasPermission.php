<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Spatie\Permission\Traits\HasPermissions;

class ModelHasPermission extends model
{
    use HasPermissions;

    protected $table = 'model_has_permissions';

    protected $fillable = [
        'permission_id',
        'model_type',
        'model_id',
    ];


    public $incrementing = false;
    public $timestamps = false;

    public function permission()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Permission::class, 'permission_id', 'id');
    }

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
