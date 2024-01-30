<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserRegister;
use App\Http\Requests\UserRequest\UpdateUser;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/users",
     *     summary="Lister tous les utilisateurs",
     *     description="Récupère la liste complète des utilisateurs enregistrés.",
     *     operationId="getUsers",
     *     tags={"Users"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des utilisateurs récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Lister Tous les Users"),
     *             @OA\Property(property="Users", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="photoProfil", type="string"),
     *                     @OA\Property(property="nom", type="string"),
     *                     @OA\Property(property="prenom", type="string"),
     *                     @OA\Property(property="email", type="string"),
     *                     @OA\Property(property="role_id", type="integer"),
     *                     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
     *                     @OA\Property(property="created_at", type="string", format="date-time"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time"),
     *                 )
     *             ),
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            "Message" => "Lister Tous les Users",
            "Users" => UserResource::collection($users)
        ]);
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/users/{id}",
     *     summary="Afficher un utilisateur",
     *     description="Récupère les informations d'un utilisateur spécifique.",
     *     operationId="getUserById",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Identifiant de l'utilisateur",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informations de l'utilisateur récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Affichage d'un Utilisateur"),
     *             @OA\Property(property="Information de l'Utilisateur", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="photoProfil", type="string"),
     *                 @OA\Property(property="nom", type="string"),
     *                 @OA\Property(property="prenom", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="role_id", type="integer"),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="L'utilisateur avec l'identifiant spécifié n'existe pas",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="L'utilisateur avec l'identifiant {id} n'existe pas.")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                "Message" => "l'Utilisateur avec l'identifiant $id n'existe pas."
            ], 404);
        }

        return response()->json([
            "Message" => "Affichage d'un Utilisateur",
            "Information de l'Utilisateur" => new UserResource($user)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $fileName = time() . "." . $request->photoProfil->extension();

    //     $image_path = $request->photoProfil->storeAs(
    //         'images_profil',
    //         $fileName,
    //         'public'
    //     );

    //     $newData = User::findOrFail($id);
    //     $newData->photoProfil = $image_path;
    //     $newData->nom = $request->nom;
    //     $newData->prenom = $request->prenom;
    //     $newData->email = $request->email;

    //     if ($newData->save()) {
    //         return response()->json([
    //             "Nouvel Donne de l'Utilisateur" => new UserResource($newData),
    //             "Message" => "Modification d'un Utilisateur Réussi !"
    //         ], 200);
    //     }
    // }
    /**
     * @OA\Post(
     *     path="/users/{id}",
     *     summary="Modifier un utilisateur",
     *     description="Modifie les informations d'un utilisateur spécifique.",
     *     operationId="updateUserById",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Identifiant de l'utilisateur à modifier",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 @OA\Property(property="photoProfil", type="string", format="binary", description="Nouvelle photo de profil en tant que fichier"),
     *                 @OA\Property(property="nom", type="string", description="Nouveau nom de l'utilisateur"),
     *                 @OA\Property(property="prenom", type="string", description="Nouveau prénom de l'utilisateur"),
     *                 @OA\Property(property="email", type="string", description="Nouvelle adresse e-mail de l'utilisateur"),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Modification de l'utilisateur réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="Nouvelle Donnée de l'Utilisateur", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="photoProfil", type="string"),
     *                 @OA\Property(property="nom", type="string"),
     *                 @OA\Property(property="prenom", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="role_id", type="integer"),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *             ),
     *             @OA\Property(property="Message", type="string", example="Modification d'un Utilisateur Réussie !"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad Request - Erreur dans les paramètres de la requête",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erreur dans les paramètres de la requête"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="L'utilisateur avec l'identifiant spécifié n'existe pas",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="L'utilisateur avec l'identifiant {id} n'existe pas.")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function update(UpdateUser $request, string $id)
    {

        $this->authorize('update', );
        $fileName = time() . "." . $request->file('photoProfil')->extension();

        $image_path = $request->file('photoProfil')->storeAs(
            'images_profil',
            $fileName,
            'public'
        );

        $newData = User::findOrFail($id);
        $newData->photoProfil = $image_path;
        $newData->nom = $request->nom;
        $newData->prenom = $request->prenom;
        $newData->email = $request->email;

        if ($newData->save()) {
            return response()->json([
                "Nouvelle Donnée de l'Utilisateur" => new UserResource($newData),
                "Message" => "Modification d'un Utilisateur Réussie !"
            ], 200);
        }

        return response("Modification de l'Utilisateur échouée");
    }
    

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/users/{id}",
     *     summary="Supprimer un utilisateur",
     *     description="Supprime un utilisateur spécifique.",
     *     operationId="deleteUserById",
     *     tags={"Users"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Identifiant de l'utilisateur à supprimer",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Utilisateur supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Suppression d'un Utilisateur Réussie !"),
     *             @OA\Property(property="Utilisateur Supprimé", type="object",
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="photoProfil", type="string"),
     *                 @OA\Property(property="nom", type="string"),
     *                 @OA\Property(property="prenom", type="string"),
     *                 @OA\Property(property="email", type="string"),
     *                 @OA\Property(property="role_id", type="integer"),
     *                 @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true),
     *                 @OA\Property(property="created_at", type="string", format="date-time"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time"),
     *             ),
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden - L'utilisateur n'est pas autorisé à supprimer cet utilisateur",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Vous n'êtes pas autorisé à supprimer cet utilisateur"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="L'utilisateur avec l'identifiant spécifié n'existe pas",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="L'utilisateur avec l'identifiant {id} n'existe pas.")
     *         )
     *     ),
     *     security={
     *         {"bearerAuth": {}}
     *     }
     * )
     */
    public function destroy(string $id)
    {
        $user = User::find($id);


        if (!$user) {
            return response()->json([
                "Message" => "Le post avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            "Message" => "Supprimer une Video",
            "Video" => new UserResource($user)
        ]);
    }
}
