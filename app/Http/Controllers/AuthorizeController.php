<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Services\Nibe\Client;

class AuthorizeController extends Controller
{
    public function capture(Request $request)
    {
        $client = new Client();
        $client->setAccessToken($request->code);
    }
}
