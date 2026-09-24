<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use App\Models\Liste;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $topFive = $request->user()
            ->listes()
            ->where('type', Liste::TYPE_TOP_FIVE)
            ->with('films')
            ->first();

        return view('dashboard', compact('topFive'));
    }
}
