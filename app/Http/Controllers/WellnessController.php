<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Mood;
use App\Models\ScentProfile;
use Illuminate\Http\Request;

class WellnessController extends Controller
{
    public function index()
    {
        $moodCategories = Mood::with(['products' => function($query) {
            $query->orderBy('effectiveness', 'desc')->take(4);
        }])->get();

        $featuredProducts = Product::whereHas('moods', function($query) {
            $query->where('effectiveness', '>=', 8);
        })->take(6)->get();

        return view('wellness.index', compact('moodCategories', 'featuredProducts'));
    }

    public function moodFinder()
    {
        $moods = Mood::all();
        $scentProfiles = ScentProfile::all();
        
        return view('wellness.mood-finder', compact('moods', 'scentProfiles'));
    }
}
