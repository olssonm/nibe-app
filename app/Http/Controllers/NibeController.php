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
        $params = http_build_query([
            'parameterIds' => [
                'indoor_temperature',
                'outdoor_temperature'
            ]
        ]);
        dd($client->request('systems/111463/parameters?' . $params));
    }
}
