<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;
use App\Services\Nibe\Client;

class SetupController extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function index(Request $request)
    {
        if (!$this->client->tokenExists()) {
            return $this->auth();
        }

        return $this->system($request);
    }

    public function auth()
    {
        return view('setup.auth');
    }

    public function system(Request $request)
    {
        $systems = $this->client->getSystems();

        return view('setup.system', [
            'systems' => $systems
        ]);
    }

    public function store(Request $request)
    {
        System::create($request->except('_token'));

        return redirect()->route('dashboard');
    }
}
