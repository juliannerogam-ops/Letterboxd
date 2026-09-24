<?php

namespace App\Http\Controllers;

use App\Models\Film;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AdminController extends Controller
{
    public function index(): View
    {
        return view('admin.index', [
            'users' => User::query()->orderBy('name')->get(),
            'films' => Film::query()->orderBy('titre')->get(),
        ]);
    }

    public function promote(User $user): RedirectResponse
    {
        $user->forceFill(['is_admin' => true])->save();

        return redirect()->route('admin.index')->with('status', $user->pseudo . ' est maintenant administrateur.');
    }
}
