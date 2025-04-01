<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\LifestyleItem;
use App\Models\ScentProfile;
use App\Models\Mood;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::where('featured', true)
            ->with(['category', 'reviews'])
            ->take(4)
            ->get();
            
        $categories = Category::take(3)->get();
        $lifestyleItems = LifestyleItem::latest()->take(3)->get();
        $scentProfiles = ScentProfile::take(5)->get();
        $moods = Mood::all();
        
        return view('home', compact(
            'featuredProducts', 
            'categories', 
            'lifestyleItems',
            'scentProfiles',
            'moods'
        ));
    }
    
    public function about()
    {
        return view('pages.about');
    }
    
    public function contact()
    {
        return view('pages.contact');
    }

    public function subscribeNewsletter(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:newsletter_subscriptions,email'
        ]);

        NewsletterSubscription::create($validated);

        return response()->json([
            'message' => 'Thank you for subscribing to our newsletter!'
        ]);
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        $products = Product::where(function($q) use ($query) {
            $q->where('name', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('short_description', 'LIKE', "%{$query}%");
        })
        ->with(['category', 'reviews'])
        ->paginate(12);
        
        return view('products.search', compact('products', 'query'));
    }

    public function scentFinder()
    {
        $moods = Mood::all();
        $scentProfiles = ScentProfile::all();
        
        return view('scent-finder.index', compact('moods', 'scentProfiles'));
    }

    public function processScentFinder(Request $request)
    {
        $validated = $request->validate([
            'mood' => 'required|exists:moods,id',
            'scent_profile' => 'required|exists:scent_profiles,id',
            'intensity' => 'required|integer|min:1|max:10'
        ]);

        $products = Product::whereHas('moods', function($query) use ($validated) {
            $query->where('moods.id', $validated['mood']);
        })
        ->whereHas('scentProfiles', function($query) use ($validated) {
            $query->where('scent_profiles.id', $validated['scent_profile'])
                  ->where('intensity', '>=', $validated['intensity']);
        })
        ->with(['category', 'reviews'])
        ->take(6)
        ->get();

        return view('scent-finder.results', compact('products'));
    }
} 