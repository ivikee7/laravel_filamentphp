<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebsiteEnquiry extends Model
{
    use SoftDeletes;

    protected $table = 'website_enquiries';

    protected $fillable = [
        'name',
        'contact_number',
        'email',
        'message',
    ];
}
