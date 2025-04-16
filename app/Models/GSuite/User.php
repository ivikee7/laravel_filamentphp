<?php

namespace App\Models\GSuite;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Model
{
    use SoftDeletes;

    protected $table = 'g_suite_users';

    protected $fillable = [
        'user_id',
        'password',
    ];
}
