@extends('layouts.app')

@section('content')
    <div class="mb-4 row">
        <div class="col-md-8">
            <h4>
                Authorization
            </h4>
            <p>
                To connect your system there are a few steps to go through:
            </p>
            <ol>
                <li>Register an account on <a href="https://www.nibeuplink.com/" target="_blank" rel="noopener noreferrer">nibeuplink.com</a></li>
                <li>Register an account and create an app on <a href="https://api.nibeuplink.com/" target="_blank" rel="noopener noreferrer">api.nibeuplink.com</a></li>
                <li>Configure this application via .env-file, set your database-credentials and Nibe application id and secret. Please check the README.md for more details.</li>
            </ol>
            <p>
                Once these prerequisites are completed, you may go ahead and authorize this application to connect to Nibe Uplink. You will be redirected to Nibe Uplink where you have to approve the persmissions to read data from your system, you will then be able to select and save what system to read data from.
            </p>
            <p>
                <a href="{{ route('auth.auth') }}" class="btn btn-primary">Authorize</a>
            </p>
        </div>
    </div>
@endsection
