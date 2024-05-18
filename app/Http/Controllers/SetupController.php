<?php

namespace App\Http\Controllers;

use App\Models\System;
use Illuminate\Http\Request;
use App\Services\Nibe\Client;

class SetupController extends Controller
{
    protected Client $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * Handle the setup procedure
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        // Setup auth
        if (!$this->client->tokenExists()) {
            return $this->client->setAccessToken('');
        }

        // If auth is completed, go to list systems
        return $this->system($request);
    }

    /**
     * Setup auth view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function auth()
    {
        return view('setup.auth');
    }

    /**
     * List the systems
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\View
     */
    public function system(Request $request)
    {
        $systems = $this->client->getSystems();

        return view('setup.system', [
            'systems' => $systems
        ]);
    }

    /**
     * Store a system
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        System::create($request->except('_token'));

        return redirect()->route('dashboard');
    }
}
