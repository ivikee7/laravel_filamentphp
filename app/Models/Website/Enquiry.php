<?php

namespace App\Models\Website;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Enquiry extends Model
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
