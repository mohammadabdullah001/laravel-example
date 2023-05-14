<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Http\Request;
use Laravel\Passport\Client;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{

    public function clients()
    {
        return response()->json([
            'data' => @auth()->user()->clients
        ]);
    }
    public function login()
    {
        if (
            auth()->attempt(
                request()->only([
                    'email',
                    'password'
                ])
            )
        ) {

            $user = auth()->user();
            return response()->json([
                'data' => $user,
                'access_token' => $user->createToken('token_hsahksahkasladkashdkad')->accessToken,
            ]);
            // $client = Client::where('id', 1)->first();
            // return $this->getTokenAndRefreshToken(
            //     $client,
            //     request('email'),
            //     request('password')
            // );
        }

        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'logout']);
    }

    public function getTokenAndRefreshToken(
        Client $client,
        $email,
        $password
    ) {

        $url = config('app.url') . '/oauth/token';
        $response = Http::asForm()->post($url, [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => $email,
            'password' => $password,
            'scope' => '*',
        ]);

        $data = $response->json();

        return response()->json([
            'data' => $data
        ]);
    }
}
