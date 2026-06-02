<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteTag extends Model
{
    protected $table = 'website_tags';

    protected $fillable = [
        'name',
        'slug',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
