<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function create(): View
    {
        $genres = Film::query()
            ->whereNotNull('genre')
            ->where('genre', '!=', '')
            ->distinct()
            ->orderBy('genre')
            ->pluck('genre');

        return view('recommendations.genre', compact('genres'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'genre' => ['required', 'string', 'exists:film,genre'],
        ]);

        $request->session()->put('preferred_genre', $validated['genre']);

        return redirect()->route('dashboard');
    }
}
