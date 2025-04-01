<?php

namespace App\Http\Controllers;

use App\Models\ScentProfile;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ScentProfileController extends Controller
{
    public function index()
    {
        $profiles = ScentProfile::withCount('products')
            ->orderBy('featured', 'desc')
            ->orderBy('name')
            ->get();

        return view('scent-profiles.index', compact('profiles'));
    }

    public function show(ScentProfile $profile)
    {
        $products = Product::with(['category', 'primaryImage'])
            ->whereHas('scentProfiles', function($query) use ($profile) {
                $query->where('id', $profile->id);
            })
            ->active()
            ->orderByPivot('intensity', 'desc')
            ->paginate(12);

        return view('scent-profiles.show', compact('profile', 'products'));
    }

    public function create()
    {
        return view('scent-profiles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:scent_profiles',
            'description' => 'required|string',
            'icon_class' => 'required|string',
            'featured' => 'boolean'
        ]);

        $profile = ScentProfile::create($validated);

        return redirect()->route('scent-profiles.show', $profile)
            ->with('success', 'Scent profile created successfully.');
    }

    public function edit(ScentProfile $profile)
    {
        return view('scent-profiles.edit', compact('profile'));
    }

    public function update(Request $request, ScentProfile $profile)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:scent_profiles,name,' . $profile->id,
            'description' => 'required|string',
            'icon_class' => 'required|string',
            'featured' => 'boolean'
        ]);

        $profile->update($validated);

        return redirect()->route('scent-profiles.show', $profile)
            ->with('success', 'Scent profile updated successfully.');
    }

    public function destroy(ScentProfile $profile)
    {
        $profile->delete();

        return redirect()->route('scent-profiles.index')
            ->with('success', 'Scent profile deleted successfully.');
    }

    public function getProducts(ScentProfile $profile)
    {
        $products = Product::with(['category', 'primaryImage'])
            ->whereHas('scentProfiles', function($query) use ($profile) {
                $query->where('id', $profile->id);
            })
            ->active()
            ->orderByPivot('intensity', 'desc')
            ->take(4)
            ->get();

        return response()->json($products);
    }

    public function getRelatedProfiles(ScentProfile $profile)
    {
        $relatedProfiles = ScentProfile::where('id', '!=', $profile->id)
            ->whereHas('products', function($query) use ($profile) {
                $query->whereIn('id', $profile->products->pluck('id'));
            })
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(3)
            ->get();

        return response()->json($relatedProfiles);
    }
} 