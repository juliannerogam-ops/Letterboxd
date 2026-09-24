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
        return view('admin.index');
    }

    public function users(): View
    {
        return view('admin.users', [
            'users' => User::query()->orderBy('name')->get(),
        ]);
    }

    public function promote(User $user): RedirectResponse
    {
        $user->forceFill(['is_admin' => true])->save();

        return redirect()->route('admin.users.index')->with('status', $user->pseudo . ' est maintenant administrateur.');
    }
}
