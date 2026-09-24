<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $lists = $request->user()->listes()->with('films')->latest()->get();

        return view('dashboard', compact('lists'));
    }
}
