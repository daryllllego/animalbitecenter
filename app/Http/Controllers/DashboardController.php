<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect to marketing dashboard as the primary view for the new site
        return redirect()->route('marketing.dashboard');
    }
}
