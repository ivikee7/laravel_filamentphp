<?php

namespace App\Models\Store;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class StoreCart extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'store_id',
        'store_product_id',
        'creator_id',
        'updater_id',
        'price',
        'quantity',
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

    public function products(): BelongsTo
    {
        return $this->belongsTo(StoreProduct::class, 'store_product_id', 'store_product_id');
    }
}
