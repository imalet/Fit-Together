<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest\RoleStore;
use App\Http\Requests\RoleRequest\RoleUpdate;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/roles",
     *     summary="Liste tous les rôles",
     *      security={
     *         {"bearerAuth": {}}
     *     },
     *     description="Récupère la liste de tous les rôles disponibles.",
     *     tags={"Role"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste de rôles récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Liste de Tous les Roles"),
     *             @OA\Property(property="Roles", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nom", type="string", example="Nom du rôle"),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur interne du serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Erreur lors de la récupération de la liste des rôles")
     *         )
     *     )
     * 
     * )
     */
    public function index()
    {
        $roles = Role::all();

        return response()->json([
            "Message" => "Liste de Tous les Roles",
            "Posts" => RoleResource::collection($roles)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/role",
     *     summary="Ajout d'un rôle",
     *     description="Ajoute un nouveau rôle à la liste des rôles disponibles.",
     *     tags={"Role"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Informations du rôle à ajouter",
     *         @OA\JsonContent(
     *             @OA\Property(property="role", type="string", example="Nouveau Rôle", description="Nom du nouveau rôle")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rôle ajouté avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Rôle ajouté avec succès !"),
     *             @OA\Property(property="Information du Rôle", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="role", type="string", example="Nouveau Rôle"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Non Authentifié")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Erreur de validation des champs")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur interne du serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Ajout d'un Rôle Échoué")
     *         )
     *     )
     * )
     */
    public function store(RoleStore $request)
    {
        $newRole = new Role();
        $newRole->role = $request->role;

        if ($newRole->save()) {
            return response()->json([
                "Message" => "Rôle ajouté avec succès !",
                "Information du Rôle" => new RoleResource($newRole)
            ], 200);
        }

        return response("Ajout d'un Rôle Échoué");
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/role/{id}",
     *     summary="Affichage d'un rôle",
     *     description="Affiche les informations détaillées d'un rôle en fonction de son identifiant.",
     *     tags={"Role"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant du rôle",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informations du rôle affichées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Affichage d'un rôle"),
     *             @OA\Property(property="Information du Rôle", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="role", type="string", example="Nom du rôle"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rôle non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Le rôle avec l'identifiant {id} n'existe pas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Non Authentifié")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                "Message" => "Le rôle avec l'identifiant $id n'existe pas."
            ], 404);
        }

        // Ajoutez la vérification de la politique d'autorisation si nécessaire
        // $this->authorize('view', $role);

        return response()->json([
            "Message" => "Affichage d'un rôle",
            "Information du Rôle" => new RoleResource($role)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/role/{id}",
     *     summary="Modification d'un rôle",
     *     description="Modifie les informations d'un rôle en fonction de son identifiant.",
     *     tags={"Role"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant du rôle à modifier",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Nouvelles informations du rôle",
     *         @OA\JsonContent(
     *             @OA\Property(property="role", type="string", example="Nouveau Nom du Rôle", description="Nouveau nom du rôle")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rôle modifié avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Rôle modifié avec succès !"),
     *             @OA\Property(property="Information du Rôle", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="role", type="string", example="Nouveau Nom du Rôle"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rôle non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Le rôle avec l'identifiant {id} n'existe pas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Non Authentifié")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Erreur de validation des champs")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur interne du serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Modification du Rôle Échouée")
     *         )
     *     )
     * )
     */
    public function update(RoleUpdate $request, string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                "Message" => "Le rôle avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $role->role = $request->input('role');

        if ($role->save()) {
            return response()->json([
                "Message" => "Rôle modifié avec succès !",
                "Information du Rôle" => new RoleResource($role)
            ], 200);
        }

        return response("Modification du Rôle Échouée");
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/role/{id}",
     *     summary="Suppression d'un rôle",
     *     description="Supprime un rôle en fonction de son identifiant.",
     *     tags={"Role"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant du rôle à supprimer",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rôle supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Rôle supprimé avec succès."),
     *             @OA\Property(property="Information du Rôle", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="role", type="string", example="Nom du rôle"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Rôle non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Le rôle avec l'identifiant {id} n'existe pas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Non Authentifié")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Impossible de supprimer le rôle",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Impossible de supprimer le rôle car il est associé à des utilisateurs.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur interne du serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Erreur lors de la suppression du Rôle")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                "Message" => "Le rôle avec l'identifiant $id n'existe pas."
            ], 404);
        }

        // Ajoutez une vérification pour s'assurer que le rôle ne peut pas être supprimé s'il est associé à des utilisateurs, par exemple
        if ($role->users()->exists()) {
            return response()->json([
                "Message" => "Impossible de supprimer le rôle car il est associé à des utilisateurs."
            ], 422);
        }

        // Ajoutez la vérification de la politique d'autorisation si nécessaire

        $role->delete();

        return response()->json([
            "Message" => "Rôle supprimé avec succès.",
            "Information du Rôle" => new RoleResource($role)
        ]);
    }
}
