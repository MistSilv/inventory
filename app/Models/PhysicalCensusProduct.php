<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhysicalCensusProduct extends Model
{
    use HasFactory;

    protected $table = 'physical_census_products';

    protected $fillable = [
        'physical_census_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Relacja do PhysicalCensus
    public function physicalCensus()
    {
        return $this->belongsTo(PhysicalCensus::class);
    }

    // Relacja do Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
