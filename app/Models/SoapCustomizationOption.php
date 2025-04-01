<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SoapCustomizationOption extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'option_name',
        'option_type',
        'price_adjustment'
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getFormattedPriceAdjustmentAttribute()
    {
        return $this->price_adjustment > 0 
            ? '+' . number_format($this->price_adjustment, 2)
            : number_format($this->price_adjustment, 2);
    }

    public function scopeColors($query)
    {
        return $query->where('option_type', 'color');
    }

    public function scopeScents($query)
    {
        return $query->where('option_type', 'scent');
    }

    public function scopeSizes($query)
    {
        return $query->where('option_type', 'size');
    }

    public function scopeShapes($query)
    {
        return $query->where('option_type', 'shape');
    }
} 