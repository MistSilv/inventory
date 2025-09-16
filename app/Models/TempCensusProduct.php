<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TempCensusProduct extends Model
{
    protected $fillable = [
        'user_id',
        'product_id',
        'name',      
        'unit',      
        'quantity',
        'price'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
