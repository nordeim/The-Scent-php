<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Mood;
use App\Models\ScentProfile;
use App\Models\Article;

class HomeController extends Controller
{
    public function index()
    {
        $featuredProducts = Product::with(['primaryImage', 'moods', 'scentProfiles'])
            ->where('featured', true)
            ->take(4)
            ->get();

        $moods = Mood::featured()->take(6)->get();
        $scentProfiles = ScentProfile::featured()->take(6)->get();
        
        $latestArticles = Article::latest()
            ->take(3)
            ->get();

        return view('home', compact(
            'featuredProducts',
            'moods',
            'scentProfiles',
            'latestArticles'
        ));
    }
}