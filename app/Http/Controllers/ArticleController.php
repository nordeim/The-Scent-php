<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::with(['tags', 'author'])
            ->published()
            ->latest()
            ->paginate(12);

        $featuredArticles = Article::with(['tags', 'author'])
            ->featured()
            ->published()
            ->latest()
            ->take(3)
            ->get();

        $popularTags = ArticleTag::withCount('articles')
            ->orderBy('articles_count', 'desc')
            ->take(10)
            ->get();

        return view('articles.index', compact('articles', 'featuredArticles', 'popularTags'));
    }

    public function show(Article $article)
    {
        if (!$article->isPublished()) {
            abort(404);
        }

        $relatedArticles = Article::with(['tags', 'author'])
            ->where('id', '!=', $article->id)
            ->whereHas('tags', function($query) use ($article) {
                $query->whereIn('id', $article->tags->pluck('id'));
            })
            ->published()
            ->latest()
            ->take(3)
            ->get();

        return view('articles.show', compact('article', 'relatedArticles'));
    }

    public function create()
    {
        $tags = ArticleTag::orderBy('name')->get();
        return view('articles.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'required|string|max:255',
            'featured_image' => 'nullable|image|max:2048',
            'tags' => 'required|array',
            'tags.*' => 'exists:article_tags,id',
            'featured' => 'boolean',
            'published' => 'boolean'
        ]);

        $article = new Article($validated);
        $article->slug = Str::slug($validated['title']);
        $article->author_id = auth()->id();

        if ($request->hasFile('featured_image')) {
            $article->featured_image = $request->file('featured_image')->store('articles', 'public');
        }

        $article->save();
        $article->tags()->attach($request->tags);

        return redirect()->route('articles.show', $article)
            ->with('success', 'Article created successfully.');
    }

    public function edit(Article $article)
    {
        $tags = ArticleTag::orderBy('name')->get();
        return view('articles.edit', compact('article', 'tags'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'excerpt' => 'required|string|max:255',
            'featured_image' => 'nullable|image|max:2048',
            'tags' => 'required|array',
            'tags.*' => 'exists:article_tags,id',
            'featured' => 'boolean',
            'published' => 'boolean'
        ]);

        $article->fill($validated);
        $article->slug = Str::slug($validated['title']);

        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            $article->featured_image = $request->file('featured_image')->store('articles', 'public');
        }

        $article->save();
        $article->tags()->sync($request->tags);

        return redirect()->route('articles.show', $article)
            ->with('success', 'Article updated successfully.');
    }

    public function destroy(Article $article)
    {
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->tags()->detach();
        $article->delete();

        return redirect()->route('articles.index')
            ->with('success', 'Article deleted successfully.');
    }

    public function tag(ArticleTag $tag)
    {
        $articles = Article::with(['tags', 'author'])
            ->whereHas('tags', function($query) use ($tag) {
                $query->where('id', $tag->id);
            })
            ->published()
            ->latest()
            ->paginate(12);

        return view('articles.tag', compact('tag', 'articles'));
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        
        $articles = Article::with(['tags', 'author'])
            ->where(function($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('content', 'like', "%{$query}%")
                  ->orWhere('excerpt', 'like', "%{$query}%");
            })
            ->published()
            ->latest()
            ->paginate(12);

        return view('articles.search', compact('articles', 'query'));
    }
} 