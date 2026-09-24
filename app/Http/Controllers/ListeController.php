<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\Liste;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class ListeController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('q')->trim()->toString();

        $lists = $request->user()
            ->listes()
            ->with('films')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('titre', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%');
                });
            })
            ->latest()
            ->get();

        return view('listes', compact('lists', 'search'));
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
            'position' => [$liste->isTopFive() ? 'required' : 'nullable', 'integer', 'between:1,5'],
        ]);

        if ($liste->isTopFive()) {
            DB::transaction(function () use ($liste, $validated): void {
                $liste->films()->detach($validated['film_id']);

                DB::table('film_liste')
                    ->where('liste_id', $liste->id)
                    ->where('position', '>=', $validated['position'])
                    ->increment('position');

                DB::table('film_liste')
                    ->where('liste_id', $liste->id)
                    ->where('position', '>', 5)
                    ->delete();

                $liste->films()->attach($validated['film_id'], [
                    'position' => $validated['position'],
                ]);
            });
        } else {
            $liste->films()->syncWithoutDetaching([$validated['film_id']]);
        }

        return redirect()->route('listes.show', $liste);
    }
}
