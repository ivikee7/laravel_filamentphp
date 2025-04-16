<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GSuiteUser extends Model
{
    use SoftDeletes;

    protected $table = 'g_suite_users';

    protected $fillable = [
        'user_id',
        'password',
    ];
}
