<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\Nibe\Client;

class NibeController extends Controller
{
    public function index()
    {
        $client = new Client();
        return redirect()->to($client->getAuthorizationUrl());
    }

    public function test()
    {
        $client = new Client();
        // dd($client->getSystems());
        dd($client->getParameters('111463'));
    }
}
