<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $system = System::first();

        if (!$system) {
            return redirect()->route('systems.index');
        }

        return view('dashboard.index', [
            'system' => $system
        ]);
    }
}
