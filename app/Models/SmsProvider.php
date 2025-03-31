<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SmsProvider extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['name', 'api_url', 'api_key', 'sender_id', 'is_active'];

    public static function getActiveProviders()
    {
        return self::where('is_active', true)->pluck('name', 'id');
    }
}
