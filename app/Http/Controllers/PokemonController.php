<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pokemon;

class PokemonController extends Controller
{
    public function list()
    {
        $pokemons = Pokemon::all();
        return view('pokemons.all', compact('pokemons'));
    }

    public function show($id)
    {
        $pokemon = Pokemon::findOrFail($id);
        return view('pokemons.show', compact('pokemon'));
    }

    public function onlyBizzares()
    {
        return Pokemon::where("name", "=", "Bulbizarre")->orderBy("level", "desc")->get();
    }

    public function delete($id)
    {
        $pokemon = Pokemon::findOrFail($id);
        $pokemon->delete();
        return redirect()->route('pokemon.list');
    }

    public function create(Request $request)
    {
        $bulbizzare = Pokemon::create([
            "name" => $request->name,
            "level" => $request->level,
            "description" => $request->description
        ]);
        return redirect()->route('pokemon.list');
    }

    public function edit_view($id)
    {
        $pokemon = Pokemon::findOrFail($id);
        return view('pokemons.edit', compact('pokemon'));
    }

    public function edit(Request $request, $id)
    {
        $pokemon = Pokemon::findOrFail($id);
        $pokemon->name = $request->name;
        $pokemon->level = $request->level;
        $pokemon->description = $request->description;
        $pokemon->save();
        return redirect()->route('pokemon.list');
    }
}
