<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'base_rate',
        'countries',
        'estimated_days'
    ];

    protected $casts = [
        'base_rate' => 'decimal:2',
        'countries' => 'array'
    ];

    public function calculateShipping($weight)
    {
        $rate = $this->base_rate;
        
        // Add weight-based rate
        if ($weight > 1) {
            $rate += ($weight - 1) * 2.50; // $2.50 per additional kg
        }
        
        return [
            'rate' => $rate,
            'estimated_days' => $this->estimated_days
        ];
    }

    public function isAvailableForCountry($countryCode)
    {
        return in_array($countryCode, $this->countries);
    }

    public function getFormattedRateAttribute()
    {
        return '$' . number_format($this->base_rate, 2);
    }

    public function getCountriesListAttribute()
    {
        return implode(', ', $this->countries);
    }

    public static function findForCountry($countryCode)
    {
        return static::whereJsonContains('countries', $countryCode)->first();
    }
} 