<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ScentProfile;
use App\Models\Mood;
use App\Models\Lifestyle;
use App\Models\EssentialOilProperty;
use App\Models\SoapCustomizationOption;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['category', 'reviews']);
        
        // Filter by category
        if ($request->has('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }
        
        // Filter by scent profile
        if ($request->has('scent')) {
            $query->whereHas('scentProfiles', function($q) use ($request) {
                $q->where('name', $request->scent);
            });
        }
        
        // Filter by mood
        if ($request->has('mood')) {
            $query->whereHas('moods', function($q) use ($request) {
                $q->where('name', $request->mood);
            });
        }
        
        // Filter by product type
        if ($request->has('type')) {
            $query->where('product_type', $request->type);
        }
        
        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%")
                  ->orWhere('short_description', 'LIKE', "%{$search}%");
            });
        }
        
        // Sort
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price-low':
                $query->orderBy('price', 'asc');
                break;
            case 'price-high':
                $query->orderBy('price', 'desc');
                break;
            case 'popular':
                $query->orderBy('review_count', 'desc');
                break;
            case 'rating':
                $query->orderBy('average_rating', 'desc');
                break;
            case 'latest':
            default:
                $query->latest();
                break;
        }
        
        $products = $query->paginate(12);
        $categories = Category::all();
        $scentProfiles = ScentProfile::all();
        $moods = Mood::all();
        
        return view('products.index', compact(
            'products', 
            'categories', 
            'scentProfiles', 
            'moods'
        ));
    }
    
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
            ->with([
                'category', 
                'ingredients', 
                'benefits', 
                'additionalImages', 
                'scentProfiles', 
                'moods',
                'reviews' => function($query) {
                    $query->with('user')->latest();
                }
            ])
            ->firstOrFail();
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();
            
        return view('products.show', compact('product', 'relatedProducts'));
    }
    
    public function storeReview(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);
        
        $review = new Review();
        $review->user_id = auth()->id();
        $review->product_id = $product->id;
        $review->rating = $validated['rating'];
        $review->comment = $validated['comment'] ?? null;
        $review->save();
        
        // Update product review count
        $product->review_count = $product->reviews()->count();
        $product->save();
        
        return redirect()->back()->with('success', 'Your review has been posted successfully!');
    }

    public function getCustomizationOptions(Product $product)
    {
        if (!$product->is_customizable) {
            return response()->json([
                'error' => 'This product is not customizable'
            ], 400);
        }

        $options = $product->customizationOptions()
            ->get()
            ->groupBy('option_type');

        return response()->json($options);
    }

    public function calculateCustomPrice(Request $request, Product $product)
    {
        if (!$product->is_customizable) {
            return response()->json([
                'error' => 'This product is not customizable'
            ], 400);
        }

        $validated = $request->validate([
            'options' => 'required|array',
            'options.*' => 'exists:soap_customization_options,id'
        ]);

        $price = $product->calculateCustomPrice($validated['options']);

        return response()->json([
            'price' => $price,
            'formatted_price' => '$' . number_format($price, 2)
        ]);
    }

    public function getEssentialOilProperties(Product $product)
    {
        if (!$product->isEssentialOil()) {
            return response()->json([
                'error' => 'This product is not an essential oil'
            ], 400);
        }

        $properties = $product->essentialOilProperties()
            ->get()
            ->pluck('property_value', 'property_name');

        return response()->json($properties);
    }

    public function indexAdmin()
    {
        $products = Product::with(['category', 'reviews'])
            ->withCount('reviews')
            ->latest()
            ->paginate(20);
            
        return view('admin.products.index', compact('products'));
    }
    
    public function createAdmin()
    {
        $categories = Category::orderBy('name')->get();
        $scentProfiles = ScentProfile::orderBy('name')->get();
        $moods = Mood::orderBy('name')->get();
        $lifestyles = Lifestyle::orderBy('name')->get();
        
        return view('admin.products.create', compact('categories', 'scentProfiles', 'moods', 'lifestyles'));
    }
    
    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products',
            'description' => 'required|string',
            'short_description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:50|unique:products',
            'weight' => 'required|numeric|min:0',
            'dimensions' => 'nullable|array',
            'dimensions.length' => 'nullable|numeric|min:0',
            'dimensions.width' => 'nullable|numeric|min:0',
            'dimensions.height' => 'nullable|numeric|min:0',
            'images' => 'required|array|min:1',
            'images.*' => 'image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'scent_profiles' => 'nullable|array',
            'scent_profiles.*' => 'exists:scent_profiles,id',
            'moods' => 'nullable|array',
            'moods.*' => 'exists:moods,id',
            'lifestyles' => 'nullable|array',
            'lifestyles.*' => 'exists:lifestyles,id',
            'intensity' => 'required|integer|min:1|max:5',
            'properties' => 'nullable|array',
            'properties.*.name' => 'required|string',
            'properties.*.value' => 'required|string',
            'customization_options' => 'nullable|array',
            'customization_options.*.name' => 'required|string',
            'customization_options.*.type' => 'required|in:color,scent,size,shape',
            'customization_options.*.price_adjustment' => 'required|numeric|min:0'
        ]);
        
        try {
            DB::beginTransaction();
            
            $data = $request->except(['images', 'scent_profiles', 'moods', 'lifestyles', 'properties', 'customization_options']);
            
            // Handle images
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
            $data['images'] = $images;
            
            // Create product
            $product = Product::create($data);
            
            // Attach relationships
            if ($request->has('scent_profiles')) {
                $product->scentProfiles()->attach($request->scent_profiles);
            }
            
            if ($request->has('moods')) {
                $product->moods()->attach($request->moods);
            }
            
            if ($request->has('lifestyles')) {
                $product->lifestyles()->attach($request->lifestyles);
            }
            
            // Create properties
            if ($request->has('properties')) {
                foreach ($request->properties as $property) {
                    $product->properties()->create($property);
                }
            }
            
            // Create customization options
            if ($request->has('customization_options')) {
                foreach ($request->customization_options as $option) {
                    $product->customizationOptions()->create($option);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error creating the product.');
        }
    }
    
    public function editAdmin(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $scentProfiles = ScentProfile::orderBy('name')->get();
        $moods = Mood::orderBy('name')->get();
        $lifestyles = Lifestyle::orderBy('name')->get();
        
        return view('admin.products.edit', compact('product', 'categories', 'scentProfiles', 'moods', 'lifestyles'));
    }
    
    public function updateAdmin(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:products,slug,' . $product->id,
            'description' => 'required|string',
            'short_description' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:50|unique:products,sku,' . $product->id,
            'weight' => 'required|numeric|min:0',
            'dimensions' => 'nullable|array',
            'dimensions.length' => 'nullable|numeric|min:0',
            'dimensions.width' => 'nullable|numeric|min:0',
            'dimensions.height' => 'nullable|numeric|min:0',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'scent_profiles' => 'nullable|array',
            'scent_profiles.*' => 'exists:scent_profiles,id',
            'moods' => 'nullable|array',
            'moods.*' => 'exists:moods,id',
            'lifestyles' => 'nullable|array',
            'lifestyles.*' => 'exists:lifestyles,id',
            'intensity' => 'required|integer|min:1|max:5',
            'properties' => 'nullable|array',
            'properties.*.name' => 'required|string',
            'properties.*.value' => 'required|string',
            'customization_options' => 'nullable|array',
            'customization_options.*.name' => 'required|string',
            'customization_options.*.type' => 'required|in:color,scent,size,shape',
            'customization_options.*.price_adjustment' => 'required|numeric|min:0'
        ]);
        
        try {
            DB::beginTransaction();
            
            $data = $request->except(['images', 'scent_profiles', 'moods', 'lifestyles', 'properties', 'customization_options']);
            
            // Handle images
            if ($request->hasFile('images')) {
                // Delete old images
                foreach ($product->images as $image) {
                    Storage::disk('public')->delete($image);
                }
                
                $images = [];
                foreach ($request->file('images') as $image) {
                    $images[] = $image->store('products', 'public');
                }
                $data['images'] = $images;
            }
            
            // Update product
            $product->update($data);
            
            // Sync relationships
            $product->scentProfiles()->sync($request->scent_profiles ?? []);
            $product->moods()->sync($request->moods ?? []);
            $product->lifestyles()->sync($request->lifestyles ?? []);
            
            // Update properties
            $product->properties()->delete();
            if ($request->has('properties')) {
                foreach ($request->properties as $property) {
                    $product->properties()->create($property);
                }
            }
            
            // Update customization options
            $product->customizationOptions()->delete();
            if ($request->has('customization_options')) {
                foreach ($request->customization_options as $option) {
                    $product->customizationOptions()->create($option);
                }
            }
            
            DB::commit();
            
            return redirect()->route('admin.products.index')
                ->with('success', 'Product updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error updating the product.');
        }
    }
    
    public function destroyAdmin(Product $product)
    {
        try {
            DB::beginTransaction();
            
            // Delete images
            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image);
            }
            
            // Delete relationships
            $product->scentProfiles()->detach();
            $product->moods()->detach();
            $product->lifestyles()->detach();
            $product->properties()->delete();
            $product->customizationOptions()->delete();
            
            $product->delete();
            
            DB::commit();
            
            return back()->with('success', 'Product deleted successfully.');
            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'There was an error deleting the product.');
        }
    }
    
    public function showAdmin(Product $product)
    {
        $product->load(['category', 'reviews', 'scentProfiles', 'moods', 'lifestyles', 'properties', 'customizationOptions']);
        
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->with(['reviews', 'category'])
            ->latest()
            ->take(4)
            ->get();
            
        return view('products.show', compact('product', 'relatedProducts'));
    }
    
    public function toggleFeaturedAdmin(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);
        
        return back()->with('success', 'Product featured status updated successfully.');
    }
    
    public function toggleActiveAdmin(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        
        return back()->with('success', 'Product active status updated successfully.');
    }
    
    public function searchAdmin(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2',
            'category' => 'nullable|exists:categories,id',
            'scent_profile' => 'nullable|exists:scent_profiles,id',
            'mood' => 'nullable|exists:moods,id',
            'lifestyle' => 'nullable|exists:lifestyles,id',
            'min_price' => 'nullable|numeric|min:0',
            'max_price' => 'nullable|numeric|gt:min_price',
            'sort' => 'nullable|in:price_asc,price_desc,newest,popular'
        ]);
        
        $query = Product::query()
            ->with(['reviews', 'category'])
            ->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->query}%")
                    ->orWhere('description', 'like', "%{$request->query}%")
                    ->orWhere('short_description', 'like', "%{$request->query}%");
            });
            
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        if ($request->has('scent_profile')) {
            $query->whereHas('scentProfiles', function($q) use ($request) {
                $q->where('scent_profiles.id', $request->scent_profile);
            });
        }
        
        if ($request->has('mood')) {
            $query->whereHas('moods', function($q) use ($request) {
                $q->where('moods.id', $request->mood);
            });
        }
        
        if ($request->has('lifestyle')) {
            $query->whereHas('lifestyles', function($q) use ($request) {
                $q->where('lifestyles.id', $request->lifestyle);
            });
        }
        
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }
        
        switch ($request->sort) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->latest();
                break;
            case 'popular':
                $query->orderBy('reviews_count', 'desc');
                break;
            default:
                $query->latest();
        }
        
        $products = $query->paginate(12);
        
        return view('products.search', compact('products'));
    }
} 