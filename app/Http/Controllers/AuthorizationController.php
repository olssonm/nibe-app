<?php

namespace App\Http\Controllers;

use App\Http\Requests\NibeCallbackRequest;
use Illuminate\Support\Str;
use App\Services\Nibe\Client;

class AuthorizationController extends Controller
{
    /**
     * Redirect to Uplink's /oauth/authorize
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function auth()
    {
        // Set a state that will later be validated on return
        $state = Str::random();
        session([
            'state' => $state
        ]);

        $client = new Client();
        return redirect()->to($client->getAuthorizationUrl([
            'state' => $state
        ]));
    }

    /**
     * Handle the callback response
     *
     * @param NibeCallbackRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function callback(NibeCallbackRequest $request)
    {
        $client = new Client();
        $client->setAccessToken($request->code);

        // Remove the state-session
        session()->forget('state');

        // Redirect to dashboard
        return redirect()->route('setup.index');
    }
}
