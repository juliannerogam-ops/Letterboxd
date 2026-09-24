<?php

namespace App\Http\Controllers;

use App\Models\Film;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RecommendationController extends Controller
{
    public function create(): View
    {
        $genres = $this->availableGenres();

        return view('recommendations.genre', compact('genres'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'genre' => ['required', 'string', 'regex:/^[^,]+$/', Rule::in($this->availableGenres())],
        ]);

        $request->session()->put('preferred_genre', $validated['genre']);

        return redirect()->route('dashboard');
    }

    private function availableGenres(): array
    {
        return Film::query()
            ->whereNotNull('genre')
            ->where('genre', '!=', '')
            ->pluck('genre')
            ->flatMap(fn (string $genres): array => array_map('trim', explode(',', $genres)))
            ->filter()
            ->unique()
            ->sort()
            ->values()
            ->all();
    }
}
