<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRegister;
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
    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Authentification de l'utilisateur",
     *     description="Authentifie un utilisateur et génère un jeton d'accès.",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Informations d'authentification de l'utilisateur",
     *         @OA\JsonContent(
     *             required={"email", "password"},
     *             @OA\Property(property="email", type="string", format="email", example="utilisateur@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="motdepasse123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur authentifié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Utilisateur Authentifié"),
     *             @OA\Property(property="Autorisation", type="object",
     *                 @OA\Property(property="Token", type="string", example="votre_jeton_d_acces"),
     *                 @OA\Property(property="Type", type="string", example="bearer")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Erreur d'authentification",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Erreur d'Authentification")
     *         )
     *     )
     * )
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
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Enregistrement d'un nouvel utilisateur",
     *     description="Enregistre un nouvel utilisateur avec les informations fournies.",
     *     tags={"Authentification"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Informations d'enregistrement de l'utilisateur",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="photoProfil", type="string", format="binary", description="Photo de profil de l'utilisateur (image)"),
     *                 @OA\Property(property="nom", type="string", example="Doe", description="Nom de l'utilisateur"),
     *                 @OA\Property(property="prenom", type="string", example="John", description="Prénom de l'utilisateur"),
     *                 @OA\Property(property="email", type="string", format="email", example="john.doe@example.com", description="Adresse e-mail de l'utilisateur"),
     *                 @OA\Property(property="password", type="string", format="password", example="motdepasse123", description="Mot de passe de l'utilisateur")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur enregistré avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Nouvel Utilisateur", type="object", description="Détails de l'utilisateur enregistré"),
     *             @OA\Property(property="Message", type="string", example="Création d'Utilisateur Réussie !")
     *         )
     *     )
     * )
     */
    public function register(UserRegister $request)
    {
        // $fileName = time() . "." . $request->photoProfil->extension();

        // $image_path = $request->photoProfil->storeAs(
        //     'images_profil',
        //     $fileName,
        //     'public'
        // );

        $newUser = new User();
        // $newUser->photoProfil = $image_path;
        $newUser->nom = $request->nom;
        $newUser->prenom = $request->prenom;
        $newUser->email = $request->email;
        $newUser->role_id = 3;
        $newUser->password = Hash::make($request->password);

        if ($newUser->save()) {
            return response()->json([
                "Nouvel Utilisateur" => $newUser,
                "Message" => "Creation d'Utilisateur Réussi !"
            ], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Déconnexion de l'utilisateur",
     *     description="Déconnecte l'utilisateur actuellement authentifié.",
     *     tags={"Authentification"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur déconnecté avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Utilisateur Déconnecté !")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non connecté",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Non Connecté")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        try {
            Auth::guard('api')->logout();
            return response()->json([
                "Message" => "Utilisateur Deconnecté ! "
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "Erreur" => "Non Connecté"
            ]);
        }
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

    /**
     * @OA\Post(
     *     path="/api/updatePassword",
     *     summary="Mise à jour du mot de passe de l'utilisateur",
     *     description="Modifie le mot de passe de l'utilisateur avec les informations fournies.",
     *     tags={"Authentification"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Informations de mise à jour du mot de passe",
     *         @OA\JsonContent(
     *             required={"email", "newPassword"},
     *             @OA\Property(property="email", type="string", format="email", example="utilisateur@example.com", description="Adresse e-mail de l'utilisateur"),
     *             @OA\Property(property="newPassword", type="string", format="password", example="nouveaumotdepasse123", description="Nouveau mot de passe de l'utilisateur")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mot de passe modifié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Mot de passe Modifié avec Succès")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Utilisateur non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Utilisateur non Trouvé")
     *         )
     *     )
     * )
     */
    public function updatePassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response('User non Trouvé');
        }

        $user->password = Hash::make($request->newPassword);
        $user->save();
        return response('Mot de passe Modifié avec Success');
    }

    public function nonConnecte()
    {
        return response()->json([
            "Message" => "Veillez vous connecter"
        ]);
    }
}
