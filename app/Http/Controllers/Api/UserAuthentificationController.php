<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserAuthentificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Attempting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthentificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function login(UserAuthentificationRequest $request)
    {
        $credentials = $request->validated();

        if ($token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                "Message" => "Utilisateur Authentifié",
                "Autorisation" => [
                    "Token" => $token,
                    "Type" => "bearer"
                ]
            ]);
        }

        return response()->json([
            "Message" => "Erreur d'Authentification"
        ], 401);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function register(UserAuthentificationRequest $request)
    {
        $newUser = new User();
        $newUser->name = $request->name;
        $newUser->email = $request->email;
        $newUser->role_id = $request->role_id;
        $newUser->password = Hash::make($request->password);

        if ($newUser->save()) {
            return response()->json([
                "Message" => "Creation d'Utilisateur Réussi !"
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function logout()
    {
        Auth::guard('api')->logout();
        return response()->json([
            "Message" => "Utilisateur Deconnecté ! "
        ], 200);
    }

    public function refresh()
    {
        try {

            return response()->json([
                'status' => 'success',
                'user' => Auth::guard('api')->user(),
                'authorisation' => [
                    'token' => Auth::guard('api')->refresh(),
                    'type' => 'bearer',
                ]
            ]);
        } catch (\Throwable $e) {
            return response()->json(["Error" => "Token Invalide"]);
        }
    }
}
