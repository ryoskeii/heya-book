<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Basic landing page for auth0 callback
     * 
     * @return void
     */
    public function callback()
    {
        echo "<h1>Login successful</h1>";
        echo "Auth0 Login Successful. Please use POST http://localhost:80/api/auth/token with your username and password credentials to generate a Bearer token to access the REST APIs";
    }

    /**
     * Generates a JWT token using the caller's Auth0 username and password to generate a token that can be used to authenticate users.
     * Docs: https://auth0.com/docs/secure/tokens/access-tokens/get-access-tokens
     * 
     * @return array A JSON payload with the JWT and some metadata
     */
    public function getToken(Request $request)
    {
        $credentials = json_decode($request->getContent(), true);
        $curl = curl_init();

        $authZeroAppDomain = getenv('AUTH0_DOMAIN');
        $authZeroClientId = getenv('AUTH0_CLIENT_ID');
        $authZeroClientSecret = getenv('AUTH0_CLIENT_SECRET');
        $authZeroAudience = getenv('AUTH0_AUDIENCE') ?? 'http://localhost:80';

        curl_setopt_array($curl, [
            CURLOPT_URL => "$authZeroAppDomain/oauth/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => implode('&', [
                'grant_type=password',
                "client_id=$authZeroClientId",
                'username=' . $credentials['username'],
                'password=' . $credentials['password'],
                "client_secret=$authZeroClientSecret",
                "audience=$authZeroAudience",
            ]),
            CURLOPT_HTTPHEADER => [
                "content-type: application/x-www-form-urlencoded",
            ],
        ]);

        $tokenData = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return response('Forbidden', 403);
        }

        // Registers the Auth0 User in the DB for later use
        if (!User::where('email', $credentials['username'])->exists()) {
            $newUser = new User();
            $newUser->fill([
                'name' => $credentials['username'],
                'email' => $credentials['username'],
                'password' => $credentials['password'],
            ]);
            $newUser->save();
        }
        
        return response()->json(json_decode($tokenData));
    }
}
