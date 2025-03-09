<?php

namespace App\Models\Store;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class StoreProduct extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'creator_id',
        'updater_id',
        'name',
        'price',
    ];

    // relationship
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id', 'id');
    }
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updater_id', 'id');
    }
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }
}
