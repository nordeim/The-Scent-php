<?php

namespace App\Http\Controllers;

use App\Models\Mood;
use Illuminate\Http\Request;

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
        $products = $mood->products()
            ->with(['primaryImage'])
            ->paginate(12);

        return view('moods.show', compact('mood', 'products'));
    }
}