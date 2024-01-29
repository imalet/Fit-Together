<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InformationComplementaireRequest\StoreInformationComplementaire;
use App\Http\Requests\InformationComplementaireRequest\UpdateInformationComplementaire;
use App\Http\Resources\InformationCompleteResource;
use App\Models\InformationComplementaire;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Http\Request;

class InformationComplementaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/informations/complementaires",
     *     summary="Liste des informations complémentaires",
     *     description="Récupère la liste de toutes les informations complémentaires.",
     *     tags={"Information Complémentaire"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des informations complémentaires récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Lister Tous les Users"),
     *             @OA\Property(property="Users", type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="nom", type="string", example="Nom de l'utilisateur"),
     *                     @OA\Property(property="prenom", type="string", example="Prénom de l'utilisateur"),
     *                     @OA\Property(property="email", type="string", example="utilisateur@example.com"),
     *                     @OA\Property(property="role_id", type="integer", example=1),
     *                     @OA\Property(property="photoProfil", type="string", example="path/vers/photo.jpg"),
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
     *             @OA\Property(property="Erreur", type="string", example="Erreur lors de la récupération des informations complémentaires")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $informationComplementaires = InformationComplementaire::all();

        return response()->json([
            "Message" => "Lister Tous les Users",
            "Users" => InformationCompleteResource::collection($informationComplementaires)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {

    //     $this->authorize('create', InformationComplementaire::class);

    //     $informationComplementaire = new InformationComplementaire();
    //     $informationComplementaire->user_id = $request->user()->id;
    //     $informationComplementaire->bio = $request->bio;
    //     $informationComplementaire->qualification = $request->qualification;
    //     $informationComplementaire->experience = $request->experience;

    //     if ($informationComplementaire->save()) {
    //         return response()->json([
    //             "Message" => "Information Complementaires Ajouté avec Success !",
    //             "Information Complementaire" => new InformationCompleteResource($informationComplementaire)
    //         ], 200);
    //     }
    //     return response("Ajout d'Information Complementaires Echoué !");
    // }
    /**
     * @OA\Post(
     *     path="/api/information/omplementaire",
     *     summary="Ajout d'informations complémentaires",
     *     description="Ajoute des informations complémentaires pour un utilisateur.",
     *     tags={"Information Complémentaire"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données d'entrée pour ajouter des informations complémentaires",
     *         @OA\JsonContent(
     *             @OA\Property(property="bio", type="string", example="Description de l'utilisateur"),
     *             @OA\Property(property="qualification", type="string", example="Qualification de l'utilisateur"),
     *             @OA\Property(property="experience", type="string", example="Expérience de l'utilisateur")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informations complémentaires ajoutées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Informations Complémentaires Ajoutées avec Succès !"),
     *             @OA\Property(property="Information Complementaire", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="bio", type="string", example="Description de l'utilisateur"),
     *                 @OA\Property(property="qualification", type="string", example="Qualification de l'utilisateur"),
     *                 @OA\Property(property="experience", type="string", example="Expérience de l'utilisateur"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Non autorisé")
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
     *             @OA\Property(property="Erreur", type="string", example="Ajout d'Informations Complémentaires Échoué !")
     *         )
     *     )
     * )
     */
    public function store(StoreInformationComplementaire $request)
    {
        $this->authorize('create', InformationComplementaire::class);

        $informationComplementaire = new InformationComplementaire();
        $informationComplementaire->user_id = $request->user()->id;
        $informationComplementaire->bio = $request->input('bio');
        $informationComplementaire->qualification = $request->input('qualification');
        $informationComplementaire->experience = $request->input('experience');

        if ($informationComplementaire->save()) {
            return response()->json([
                "Message" => "Informations Complementaires Ajoutées avec Succès !",
                "Information Complementaire" => new InformationCompleteResource($informationComplementaire)
            ], 200);
        }

        return response("Ajout d'Informations Complementaires Échoué !");
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/information/complementaire/{id}",
     *     summary="Affichage d'une information complémentaire",
     *     description="Récupère les détails d'une information complémentaire en fonction de son identifiant.",
     *     tags={"Information Complémentaire"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de l'information complémentaire à afficher",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Information complémentaire récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Affichage d'une Information Complémentaire"),
     *             @OA\Property(property="Information Complementaire", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="bio", type="string", example="Description de l'utilisateur"),
     *                 @OA\Property(property="qualification", type="string", example="Qualification de l'utilisateur"),
     *                 @OA\Property(property="experience", type="string", example="Expérience de l'utilisateur"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Information complémentaire non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="L'information Complementaire avec l'identifiant {id} n'existe pas.")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $informationComplementaire = InformationComplementaire::find($id);

        if (!$informationComplementaire) {
            return response()->json([
                "Message" => "L'information Complementaire avec l'identifiant $id n'existe pas."
            ], 404);
        }

        return response()->json([
            "Message" => "Affichage d'une Video",
            "Information de la Video" => new InformationCompleteResource($informationComplementaire)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {

    //     $informationComplementaire = InformationComplementaire::findOrFail($id);

    //     $this->authorize('update', $informationComplementaire);

    //     $informationComplementaire->bio = $request->bio;
    //     $informationComplementaire->qualification = $request->qualification;
    //     $informationComplementaire->experience = $request->experience;

    //     if ($informationComplementaire->save()) {
    //         return response()->json([
    //             "Message" => "Information Complementaires Modifié avec Success !",
    //             "Information Complementaires" => new InformationCompleteResource($informationComplementaire)
    //         ], 200);
    //     }
    //     return response("Moficication d'Information Complementaires Echoué !");
    // }
    /**
     * @OA\Post(
     *     path="/api/information/complementaire/{id}",
     *     summary="Modification d'informations complémentaires",
     *     description="Modifie les informations complémentaires pour un utilisateur en fonction de son identifiant.",
     *     tags={"Information Complémentaire"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de l'information complémentaire à modifier",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Données d'entrée pour la modification des informations complémentaires",
     *         @OA\JsonContent(
     *             @OA\Property(property="bio", type="string", example="Nouvelle description de l'utilisateur"),
     *             @OA\Property(property="qualification", type="string", example="Nouvelle qualification de l'utilisateur"),
     *             @OA\Property(property="experience", type="string", example="Nouvelle expérience de l'utilisateur")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informations complémentaires modifiées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Informations Complémentaires Modifiées avec Succès !"),
     *             @OA\Property(property="Information Complementaires", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="bio", type="string", example="Nouvelle description de l'utilisateur"),
     *                 @OA\Property(property="qualification", type="string", example="Nouvelle qualification de l'utilisateur"),
     *                 @OA\Property(property="experience", type="string", example="Nouvelle expérience de l'utilisateur"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Information complémentaire non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="L'information Complementaire avec l'identifiant {id} n'existe pas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non autorisé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Non autorisé")
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
     *             @OA\Property(property="Erreur", type="string", example="Modification d'Informations Complementaires Échouée !")
     *         )
     *     )
     * )
     */
    public function update(UpdateInformationComplementaire $request, string $id)
    {
        $informationComplementaire = InformationComplementaire::find($id);

        $this->authorize('update', $informationComplementaire);

        $informationComplementaire->bio = $request->input('bio');
        $informationComplementaire->qualification = $request->input('qualification');
        $informationComplementaire->experience = $request->input('experience');

        if ($informationComplementaire->save()) {
            return response()->json([
                "Message" => "Informations Complementaires Modifiées avec Succès !",
                "Information Complementaires" => new InformationCompleteResource($informationComplementaire)
            ], 200);
        }

        return response("Modification d'Informations Complementaires Échouée !");
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/informations-complementaires/{id}",
     *     summary="Suppression d'une information complémentaire",
     *     description="Supprime une information complémentaire en fonction de son identifiant.",
     *     tags={"Information Complémentaire"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de l'information complémentaire à supprimer",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Information complémentaire supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Supprimer une Information Complementaire"),
     *             @OA\Property(property="Information Complementaire", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="bio", type="string", example="Description de l'utilisateur"),
     *                 @OA\Property(property="qualification", type="string", example="Qualification de l'utilisateur"),
     *                 @OA\Property(property="experience", type="string", example="Expérience de l'utilisateur"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Information complémentaire non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="L'information Complementaire avec l'identifiant {id} n'existe pas.")
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
    public function destroy(string $id)
    {
        $informationComplementaire = InformationComplementaire::find($id);

        if (!$informationComplementaire) {
            return response()->json([
                "Message" => "L'information Complementaire avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $this->authorize('delete', $informationComplementaire);

        $informationComplementaire->delete();

        return response()->json([
            "Message" => "Supprimer une Information Complementaire",
            "Information Complementaire" => new InformationCompleteResource($informationComplementaire)
        ]);
    }
}
