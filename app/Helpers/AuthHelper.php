<?php

namespace App\Helpers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthHelper
{
    /**
     * Helper function which extrapolates the mapping between API caller and the user table (via the JWT and Auth0 API)
     * Auth0 Docs for GET /users/{id}
     * https://auth0.com/docs/manage-users/user-search/retrieve-users-with-get-users-by-id-endpoint
     * 
     * @return User
     */
    public static function getCallerIdentity(Request $request): User
    {
        $jwt = explode('.', $request->bearerToken());
        $payload = json_decode(base64_decode($jwt[1]), true);
        $subject = $payload['sub'];

        $curl = curl_init();

        $authZeroAppDomain = getenv('AUTH0_DOMAIN');
        $authZeroManagementApiKey = getenv('AUTH0_MANAGEMENT_API_KEY');

        curl_setopt_array($curl, [
            CURLOPT_URL => "$authZeroAppDomain/api/v2/users/$subject",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => [
                "authorization: Bearer $authZeroManagementApiKey",
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return null; // Edge case
        }

        $callerEmail = json_decode($response, true)['email'];
        return User::where('email', $callerEmail)->first();
    }
}
