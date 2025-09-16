<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StocktakingAdjustment extends Model
{
    protected $table = 'stocktaking_adjustments';

    protected $fillable = [
        'region_stocktaking_id',
        'product_id',
        'product_name',
        'barcode',
        'unit',
        'quantity',
        'unit_price',
        'adjusted_by',
    ];

    public function regionStocktaking()
    {
        return $this->belongsTo(RegionStocktaking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }
}

