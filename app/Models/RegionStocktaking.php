<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RegionStocktaking extends Model
{
    use HasFactory;

    protected $fillable = ['stocktaking_id', 'region_id'];

    // Optional: define relationships
    public function stocktaking()
    {
        return $this->belongsTo(Stocktaking::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function censuses()
    {
        return $this->hasMany(PhysicalCensus::class);
    }

    public function adjustments()
    {
        return $this->hasMany(StocktakingAdjustment::class);
    }



}
