<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Liste;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ListeController extends Controller
{
    public function index(Request $request): View
    {
        $lists = $request->user()->listes()->with('films')->latest()->get();

        return view('listes', compact('lists'));
    }

    public function create(): View
    {
        return view('listes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'titre' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $request->user()->listes()->create($validated);

        return redirect()->route('listes');
    }

    public function show(Request $request, Liste $liste): View
    {
        abort_unless($liste->user_id === $request->user()->id, 403);

        $liste->load('films');
        $films = Film::query()->orderBy('titre')->get();

        return view('listes.show', compact('liste', 'films'));
    }

    public function addFilm(Request $request, Liste $liste): RedirectResponse
    {
        abort_unless($liste->user_id === $request->user()->id, 403);

        $validated = $request->validate([
            'film_id' => ['required', 'uuid', 'exists:film,id'],
        ]);

        $liste->films()->syncWithoutDetaching([$validated['film_id']]);

        return redirect()->route('listes.show', $liste);
    }
}
