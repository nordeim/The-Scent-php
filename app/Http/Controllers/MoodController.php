<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MoodController extends Controller
{
    public function index()
    {
        $moods = Mood::withCount('products')
            ->orderBy('featured', 'desc')
            ->orderBy('name')
            ->get();

        return view('moods.index', compact('moods'));
    }

    public function show(Mood $mood)
    {
        $products = Product::with(['category', 'primaryImage'])
            ->whereHas('moods', function($query) use ($mood) {
                $query->where('id', $mood->id);
            })
            ->active()
            ->orderByPivot('effectiveness', 'desc')
            ->paginate(12);

        return view('moods.show', compact('mood', 'products'));
    }

    public function create()
    {
        return view('moods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:moods',
            'description' => 'required|string',
            'icon_class' => 'required|string',
            'featured' => 'boolean'
        ]);

        $mood = Mood::create($validated);

        return redirect()->route('moods.show', $mood)
            ->with('success', 'Mood profile created successfully.');
    }

    public function edit(Mood $mood)
    {
        return view('moods.edit', compact('mood'));
    }

    public function update(Request $request, Mood $mood)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:moods,name,' . $mood->id,
            'description' => 'required|string',
            'icon_class' => 'required|string',
            'featured' => 'boolean'
        ]);

        $mood->update($validated);

        return redirect()->route('moods.show', $mood)
            ->with('success', 'Mood profile updated successfully.');
    }

    public function destroy(Mood $mood)
    {
        $mood->delete();

        return redirect()->route('moods.index')
            ->with('success', 'Mood profile deleted successfully.');
    }

    public function getProducts(Mood $mood)
    {
        $products = Product::with(['category', 'primaryImage'])
            ->whereHas('moods', function($query) use ($mood) {
                $query->where('id', $mood->id);
            })
            ->active()
            ->orderByPivot('effectiveness', 'desc')
            ->take(4)
            ->get();

        return response()->json($products);
    }

    public function getRelatedMoods(Mood $mood)
    {
        $relatedMoods = Mood::where('id', '!=', $mood->id)
            ->whereHas('products', function($query) use ($mood) {
                $query->whereIn('id', $mood->products->pluck('id'));
            })
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(3)
            ->get();

        return response()->json($relatedMoods);
    }
} 