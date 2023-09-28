<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class dashboardController extends Controller
{
    function dashboard() : View {
        return view('dashboard.dashboard');
    }
}
