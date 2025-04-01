<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EssentialOilProperty extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'property_name',
        'property_value'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeTherapeutic($query)
    {
        return $query->where('property_name', 'therapeutic_properties');
    }

    public function scopeSafety($query)
    {
        return $query->where('property_name', 'safety_notes');
    }

    public function scopeUsage($query)
    {
        return $query->where('property_name', 'usage_instructions');
    }

    public function scopeBotanical($query)
    {
        return $query->where('property_name', 'botanical_name');
    }

    public function scopeOrigin($query)
    {
        return $query->where('property_name', 'origin');
    }

    public function scopeExtraction($query)
    {
        return $query->where('property_name', 'extraction_method');
    }

    public function scopeShelfLife($query)
    {
        return $query->where('property_name', 'shelf_life');
    }
} 