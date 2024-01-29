<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SousCategorieRequest\StoreSousCategorie;
use App\Http\Requests\SousCategorieRequest\UpdateSousCategorie;
use App\Http\Resources\SousCategorieResource;
use App\Models\SousCategorie;
use Illuminate\Http\Request;

class SousCategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/sous-categories",
     *     summary="Liste des sous-catégories",
     *     description="Récupère la liste de toutes les sous-catégories.",
     *     tags={"Sous-Catégorie"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des sous-catégories récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="sous_categorie", type="string", example="Nom de la sous-catégorie"),
     *                 @OA\Property(property="categorie_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )),
     *             @OA\Property(property="Message", type="string", example="Liste des sous-catégories")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $sousCategories = SousCategorie::all();

        return response()->json(['data' => SousCategorieResource::collection($sousCategories)]);
    }


    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'sous_categorie' => 'required',
    //         'categorie_id' => 'required|exists:categories,id',
    //     ]);

    //     $sousCategorie = new SousCategorie();
    //     $sousCategorie->sous_categorie = $request->sous_categorie;
    //     $sousCategorie->categorie_id = $request->categorie_id;
    //     $sousCategorie->save();

    //     return response()->json(['message' => 'Sous-categorie created successfully', 'data' => new SousCategorieResource($sousCategorie)], 201);
    // }
    /**
     * @OA\Post(
     *     path="/api/sous-categorie",
     *     summary="Création d'une sous-catégorie",
     *     description="Crée une nouvelle sous-catégorie.",
     *     tags={"Sous-Catégorie"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="sous_categorie", type="string", example="Nom de la sous-catégorie"),
     *             @OA\Property(property="categorie_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Sous-catégorie créée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sous-categorie créée avec succès"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="sous_categorie", type="string", example="Nom de la sous-catégorie"),
     *                 @OA\Property(property="categorie_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Non autorisé")
     *         )
     *     )
     * )
     */
    public function store(StoreSousCategorie $request)
    {
        $this->authorize('create', SousCategorie::class);

        $sousCategorie = new SousCategorie();
        $sousCategorie->sous_categorie = $request->sous_categorie;
        $sousCategorie->categorie_id = $request->categorie_id;
        $sousCategorie->save();

        return response()->json([
            'message' => 'Sous-categorie créée avec succès',
            'data' => new SousCategorieResource($sousCategorie)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/sous-categorie/{id}",
     *     summary="Affichage d'une sous-catégorie",
     *     description="Récupère les informations d'une sous-catégorie spécifique.",
     *     tags={"Sous-Catégorie"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la sous-catégorie",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informations de la sous-catégorie récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="sous_categorie", type="string", example="Nom de la sous-catégorie"),
     *                 @OA\Property(property="categorie_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La sous-catégorie avec l'identifiant spécifié n'existe pas",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="La sous-catégorie avec l'identifiant $id n'existe pas.")
     *         )
     *     )
     * )
     */
    public function show(String $id)
    {
        $sousCategorie = SousCategorie::find($id);

        if (!$sousCategorie) {
            return response()->json([
                "Message" => "L'information Complementaire avec l'identifiant $id n'existe pas."
            ], 404);
        }

        return response()->json(['data' => new SousCategorieResource($sousCategorie)]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, String $id)
    // {
    //     $sousCategorie = SousCategorie::find($id);

    //     $request->validate([
    //         'sous_categorie' => 'required',
    //         'categorie_id' => 'required|exists:categories,id',
    //     ]);

    //     $sousCategorie->sous_categorie = $request->sous_categorie;
    //     $sousCategorie->categorie_id = $request->categorie_id;
    //     $sousCategorie->update();

    //     return response()->json(['message' => 'Sous-categorie updated successfully', 'data' => new SousCategorieResource($sousCategorie)]);
    // }
    /**
     * @OA\Post(
     *     path="/api/sous-categorie/{id}",
     *     summary="Mise à jour d'une sous-catégorie",
     *     description="Modifie les informations d'une sous-catégorie spécifique.",
     *     tags={"Sous-Catégorie"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la sous-catégorie",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="sous_categorie", type="string", example="Nouveau nom de la sous-catégorie"),
     *             @OA\Property(property="categorie_id", type="integer", example=1)
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sous-catégorie mise à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sous-categorie mise à jour avec succès"),
     *             @OA\Property(property="data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="sous_categorie", type="string", example="Nouveau nom de la sous-catégorie"),
     *                 @OA\Property(property="categorie_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La sous-catégorie avec l'identifiant spécifié n'existe pas",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="La sous-catégorie avec l'identifiant $id n'existe pas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erreur de validation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The given data was invalid."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Non autorisé")
     *         )
     *     )
     * )
     */
    public function update(UpdateSousCategorie $request, string $id)
    {
        $sousCategorie = SousCategorie::find($id);

        if (!$sousCategorie) {
            return response()->json([
                "Message" => "La sous-catégorie avec l'identifiant $id n'existe pas."
            ], 404);
        }
        $this->authorize('update', $sousCategorie);


        $sousCategorie->sous_categorie = $request->input('sous_categorie');
        $sousCategorie->categorie_id = $request->input('categorie_id');
        $sousCategorie->save();

        return response()->json([
            'message' => 'Sous-categorie mise à jour avec succès',
            'data' => new SousCategorieResource($sousCategorie)
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/sous-categorie/{id}",
     *     summary="Suppression d'une sous-catégorie",
     *     description="Supprime une sous-catégorie spécifique.",
     *     tags={"Sous-Catégorie"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la sous-catégorie",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Sous-catégorie supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Sous-categorie deleted successfully"),
     *             @OA\Property(property="Data", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="sous_categorie", type="string", example="Nom de la sous-catégorie"),
     *                 @OA\Property(property="categorie_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="La sous-catégorie avec l'identifiant spécifié n'existe pas",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="La sous-catégorie avec l'identifiant $id n'existe pas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Non autorisé")
     *         )
     *     )
     * )
     */
    public function destroy(String $id)
    {
        $sousCategorie = SousCategorie::find($id);

        $this->authorize('delete', $sousCategorie);

        if (!$sousCategorie) {
            return response()->json([
                "Message" => "L'information Complementaire avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $sousCategorie->delete();

        return response()->json(['message' => 'Sous-categorie deleted successfully', 'Data' => new SousCategorieResource($sousCategorie)]);
    }
}
