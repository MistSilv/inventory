<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['id_abaco', 'name', 'price', 'unit_id'];

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function barcodes()
    {
        return $this->hasMany(Barcode::class);
    }
}
