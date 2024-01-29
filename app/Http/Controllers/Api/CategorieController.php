<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategorieRequest\StoreCategorie;
use App\Http\Requests\CategorieRequest\UpdateCategorie;
use App\Http\Resources\CategorieResource;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Liste toutes les catégories",
     *     description="Récupère la liste de toutes les catégories disponibles.",
     *     tags={"Categorie"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste de catégories récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Liste de Toutes les Catégories"),
     *             @OA\Property(property="Categories", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nom", type="string", example="Nom de la catégorie"),
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
     *             @OA\Property(property="Erreur", type="string", example="Erreur lors de la récupération de la liste des catégories")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $categories = Categorie::all();

        return CategorieResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/categorie",
     *     summary="Ajout d'une catégorie",
     *     description="Ajoute une nouvelle catégorie à la liste des catégories disponibles.",
     *     tags={"Categorie"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Informations de la catégorie à ajouter",
     *         @OA\JsonContent(
     *             @OA\Property(property="nom", type="string", example="Nouvelle Catégorie", description="Nom de la nouvelle catégorie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie ajoutée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Catégorie ajoutée avec succès"),
     *             @OA\Property(property="Information de la Catégorie", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Nouvelle Catégorie"),
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
     *             @OA\Property(property="Erreur", type="string", example="Ajout de Catégorie Échoué")
     *         )
     *     )
     * )
     */
    public function store(StoreCategorie $request)
    {
        $request->validated();

        $category = Categorie::create($request->all());

        return new CategorieResource($category);
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/categorie/{id}",
     *     summary="Affichage d'une catégorie",
     *     description="Affiche les informations détaillées d'une catégorie en fonction de son identifiant.",
     *     tags={"Categorie"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la catégorie",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informations de la catégorie affichées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Affichage d'une catégorie"),
     *             @OA\Property(property="Information de la Catégorie", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Nom de la catégorie"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Catégorie non trouvée")
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
    public function show($id)
    {
        try {
            $categorie = Categorie::findOrFail($id);
            return new CategorieResource($categorie);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/categories/{id}",
     *     summary="Mise à jour d'une catégorie",
     *     description="Met à jour les informations d'une catégorie en fonction de son identifiant.",
     *     tags={"Categorie"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la catégorie à mettre à jour",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Nouvelles informations de la catégorie",
     *         @OA\JsonContent(
     *             @OA\Property(property="categorie", type="string", example="Nouveau Nom de la Catégorie", description="Nouveau nom de la catégorie")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie mise à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Catégorie mise à jour avec succès"),
     *             @OA\Property(property="Information de la Catégorie", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Nouveau Nom de la Catégorie"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Catégorie non trouvée")
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
     *             @OA\Property(property="Erreur", type="string", example="Mise à jour de la Catégorie Échouée")
     *         )
     *     )
     * )
     */
    public function update(UpdateCategorie $request, Categorie $categorie)
    {
        $request->validated();

        $categorie->categorie = $request->categorie;
        $categorie->update();

        return new CategorieResource($categorie);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/categorie/{id}",
     *     summary="Suppression d'une catégorie",
     *     description="Supprime une catégorie en fonction de son identifiant.",
     *     tags={"Categorie"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la catégorie à supprimer",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Catégorie supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Catégorie supprimée avec succès."),
     *             @OA\Property(property="Data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="nom", type="string", example="Nom de la catégorie"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Catégorie non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="La catégorie avec l'identifiant {id} n'existe pas.")
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
     *         response=500,
     *         description="Erreur interne du serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Erreur lors de la suppression de la Catégorie")
     *         )
     *     )
     * )
     */
    public function destroy(String $id)
    {

        // $categorie->delete();
        // return response()->json(['message' => 'Category deleted successfully']);

        $categorie = Categorie::find($id);

        // $this->authorize('delete', $categorie);

        if (!$categorie) {
            return response()->json([
                "Message" => "L'information Complementaire avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $categorie->delete();

        return response()->json(['message' => 'Sous-categorie deleted successfully', 'Data' => new CategorieResource($categorie)]);
    }
}
