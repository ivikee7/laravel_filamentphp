<?php

namespace App\Models\School;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdmissionPromotion extends Model
{
    use SoftDeletes;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (auth()->check()) {
                $model->creator_id = auth()->id();
                $model->updater_id = auth()->id();
            }
        });
        static::updating(function ($model) {
            if (auth()->check()) {
                $model->updater_id = auth()->id();
            }
        });
    }
}
