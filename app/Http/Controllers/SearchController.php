<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ScentProfile;
use App\Models\Mood;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->get('q');
        $category = $request->get('category');
        $scent = $request->get('scent');
        $mood = $request->get('mood');
        $priceMin = $request->get('price_min');
        $priceMax = $request->get('price_max');
        $sort = $request->get('sort', 'relevance');
        
        $products = Product::query()
            ->with(['category', 'reviews'])
            ->when($query, function($q) use ($query) {
                $q->where(function($q) use ($query) {
                    $q->where('name', 'LIKE', "%{$query}%")
                      ->orWhere('description', 'LIKE', "%{$query}%")
                      ->orWhere('short_description', 'LIKE', "%{$query}%");
                });
            })
            ->when($category, function($q) use ($category) {
                $q->whereHas('category', function($q) use ($category) {
                    $q->where('slug', $category);
                });
            })
            ->when($scent, function($q) use ($scent) {
                $q->whereHas('scentProfiles', function($q) use ($scent) {
                    $q->where('name', $scent);
                });
            })
            ->when($mood, function($q) use ($mood) {
                $q->whereHas('moods', function($q) use ($mood) {
                    $q->where('name', $mood);
                });
            })
            ->when($priceMin, function($q) use ($priceMin) {
                $q->where('price', '>=', $priceMin);
            })
            ->when($priceMax, function($q) use ($priceMax) {
                $q->where('price', '<=', $priceMax);
            })
            ->when($sort, function($q) use ($sort) {
                switch ($sort) {
                    case 'price-low':
                        $q->orderBy('price', 'asc');
                        break;
                    case 'price-high':
                        $q->orderBy('price', 'desc');
                        break;
                    case 'rating':
                        $q->orderBy('average_rating', 'desc');
                        break;
                    case 'newest':
                        $q->latest();
                        break;
                    case 'popular':
                        $q->orderBy('review_count', 'desc');
                        break;
                    case 'relevance':
                    default:
                        if ($query) {
                            $q->orderByRaw("
                                CASE 
                                    WHEN name LIKE ? THEN 1
                                    WHEN short_description LIKE ? THEN 2
                                    WHEN description LIKE ? THEN 3
                                    ELSE 4
                                END
                            ", ["%{$query}%", "%{$query}%", "%{$query}%"]);
                        } else {
                            $q->latest();
                        }
                        break;
                }
            });
            
        $products = $products->paginate(12);
        
        $categories = Category::all();
        $scentProfiles = ScentProfile::all();
        $moods = Mood::all();
        
        return view('search.index', compact(
            'products',
            'categories',
            'scentProfiles',
            'moods',
            'query',
            'category',
            'scent',
            'mood',
            'priceMin',
            'priceMax',
            'sort'
        ));
    }
    
    public function suggest(Request $request)
    {
        $query = $request->get('q');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }
        
        $products = Product::where('name', 'LIKE', "%{$query}%")
            ->orWhere('short_description', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'slug', 'image')
            ->take(5)
            ->get();
            
        $categories = Category::where('name', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'slug')
            ->take(3)
            ->get();
            
        return response()->json([
            'products' => $products,
            'categories' => $categories
        ]);
    }
    
    public function advanced(Request $request)
    {
        $query = Product::query()
            ->with(['category', 'reviews']);
            
        // Essential Oil Properties
        if ($request->has('therapeutic_properties')) {
            $query->whereHas('essentialOilProperties', function($q) use ($request) {
                $q->where('property_name', 'therapeutic_properties')
                  ->whereIn('property_value', $request->therapeutic_properties);
            });
        }
        
        if ($request->has('safety_notes')) {
            $query->whereHas('essentialOilProperties', function($q) use ($request) {
                $q->where('property_name', 'safety_notes')
                  ->whereIn('property_value', $request->safety_notes);
            });
        }
        
        if ($request->has('usage_methods')) {
            $query->whereHas('essentialOilProperties', function($q) use ($request) {
                $q->where('property_name', 'usage_methods')
                  ->whereIn('property_value', $request->usage_methods);
            });
        }
        
        // Soap Customization
        if ($request->has('colors')) {
            $query->whereHas('customizationOptions', function($q) use ($request) {
                $q->where('option_type', 'color')
                  ->whereIn('option_name', $request->colors);
            });
        }
        
        if ($request->has('scents')) {
            $query->whereHas('customizationOptions', function($q) use ($request) {
                $q->where('option_type', 'scent')
                  ->whereIn('option_name', $request->scents);
            });
        }
        
        if ($request->has('sizes')) {
            $query->whereHas('customizationOptions', function($q) use ($request) {
                $q->where('option_type', 'size')
                  ->whereIn('option_name', $request->sizes);
            });
        }
        
        if ($request->has('shapes')) {
            $query->whereHas('customizationOptions', function($q) use ($request) {
                $q->where('option_type', 'shape')
                  ->whereIn('option_name', $request->shapes);
            });
        }
        
        // Price Range
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        
        // Rating
        if ($request->has('min_rating')) {
            $query->where('average_rating', '>=', $request->min_rating);
        }
        
        // Availability
        if ($request->has('in_stock')) {
            $query->where('stock', '>', 0);
        }
        
        // Sort
        $sort = $request->get('sort', 'relevance');
        switch ($sort) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'popular':
                $query->orderBy('review_count', 'desc');
                break;
            case 'relevance':
            default:
                $query->latest();
                break;
        }
        
        $products = $query->paginate(12);
        
        return view('search.advanced', compact('products'));
    }
} 