<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect to animal bite dashboard as the primary view
        return redirect()->route('animal-bite.dashboard');
    }
}
