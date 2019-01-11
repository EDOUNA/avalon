<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use JWTAuthException;
use Log;

class UserController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['success' => false, 'data' => 'Geen geldig e-mail.']);
        }

        $user = User::where('email', $request->email)->get()->first();

        if (!$user) {
            return response()->json(['success' => false, 'data' => 'Gebruiker niet gevonden.'], 400);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['success' => false, 'data' => 'Ongeldige gebruikersnaam en wachtwoord combinatie.'], 400);
        }

        $token = $this->getToken($request->email, $request->password);
        $user->auth_token = $token;
        $user->save();

        return response()->json(['success' => true
                , 'data' => ['id' => $user->id
                    , 'auth_token' => $user->auth_token
                    , 'name' => $user->name
                    , 'email' => $user->email]
            ]
            , 201);
    }

    /**
     * @param $email
     * @param $password
     * @return \Illuminate\Http\JsonResponse|null
     */
    private function getToken($email, $password)
    {
        $token = null;
        try {
            if (!$token = JWTAuth::attempt(['email' => $email, 'password' => $password])) {
                return response()->json([
                    'response' => 'error'
                    , 'message' => 'Password or email is invalid'
                    , 'token' => $token
                ], 500);
            }
        } catch (JWTAuthException $e) {
            Log::debug('Failed generating token');
            return response()->json([
                'response' => 'error'
                , 'message' => 'Token creation failed'
            ], 500);
        }
        return $token;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(Request $request)
    {
        $payload = [
            'password' => Hash::make($request->password)
            , 'email' => $request->email
            , 'name' => $request->name
            , 'auth_token' => ''
        ];

        $user = new User($payload);
        if (!$user->save()) {
            return response()->json(['success' => false, 'De gebruiker kon niet worden aangemaakt.'], 400);
        }

        $token = $this->getToken($request->email, $request->password);
        if (!is_string($token)) {
            return response()->json(['success' => false, 'data' => 'Token genereren mislukt.'], 500);
        }

        $user = User::where('email', $request->email)->get()->first();
        $user->auth_token = $token;
        $user->save();

        return response()->json(['success' => true
                , 'data' => ['name' => $user->name
                    , 'id' => $user->id
                    , 'email' => $request->email
                    , 'auth_token' => $token]
            ]
            , 201);
    }
}
