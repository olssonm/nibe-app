<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $system = System::first();

        // If no system is set, redirect to setup procedure
        if (!$system) {
            return redirect()->route('setup.index');
        }

        return view('dashboard.index', [
            'system' => $system
        ]);
    }
}
