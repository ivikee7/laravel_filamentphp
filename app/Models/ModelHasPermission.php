<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelHasPermission extends model
{
    protected $table = 'model_has_permissions';
    protected $primaryKey = null;
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'permission_id', 'model_id', 'model_type', 'team_id',
    ];
}
