<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentaireRequest\StoreCommentaire;
use App\Http\Requests\CommentaireRequest\UpdateCommentaire;
use App\Http\Resources\CommentaireResource;
use App\Models\Commentaire;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    /**
     * @OA\Get(
     *     path="/api/commentaires",
     *     summary="Liste tous les commentaires",
     *     description="Récupère la liste de tous les commentaires.",
     *     tags={"Commentaire"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste de commentaires récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Tous les Commentaires"),
     *             @OA\Property(property="Commentaires", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="contenu", type="string", example="Contenu du commentaire"),
     *                     @OA\Property(property="user_id", type="integer", example=1),
     *                     @OA\Property(property="post_id", type="integer", example=1),
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
     *             @OA\Property(property="Erreur", type="string", example="Erreur lors de la récupération de la liste des commentaires")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $commentaires = Commentaire::all();

        return response()->json([
            "Message" => "Tous les Commentaires",
            "Commentaires" => CommentaireResource::collection($commentaires)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/commentaire",
     *     summary="Ajout d'un commentaire",
     *     description="Ajoute un nouveau commentaire.",
     *     tags={"Commentaire"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Informations du commentaire à ajouter",
     *         @OA\JsonContent(
     *             @OA\Property(property="contenu", type="string", example="Contenu du commentaire", description="Contenu du commentaire"),
     *             @OA\Property(property="video_id", type="integer", example=1, description="Identifiant de la vidéo associée")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commentaire ajouté avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Enregistrement Réussi"),
     *             @OA\Property(property="Nouvelles Informations", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="contenu", type="string", example="Contenu du commentaire"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="video_id", type="integer", example=1),
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
     *             @OA\Property(property="Erreur", type="string", example="Insertion d'un Nouvelles information Échouée")
     *         )
     *     )
     * )
     */
    public function store(StoreCommentaire $request)
    {
        $this->authorize('create', Commentaire::class);

        $newCommentaire = new Commentaire();
        $newCommentaire->user_id = $request->user()->id;
        $newCommentaire->contenu = $request->input('contenu');
        $newCommentaire->video_id = $request->input('video_id');

        if ($newCommentaire->save()) {
            return response()->json([
                "Message" => "Enregistrement Réussi",
                "Nouvelles Informations" => new CommentaireResource($newCommentaire)
            ]);
        }

        return response()->json([
            "Message" => "Insertion d'un Nouvelles information Échouée"
        ]);
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/commentaire/{id}",
     *     summary="Affichage d'un commentaire",
     *     description="Affiche les détails d'un commentaire en fonction de son identifiant.",
     *     tags={"Commentaire"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant du commentaire à afficher",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commentaire affiché avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Tous les Commentaires"),
     *             @OA\Property(property="Commentaires", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="contenu", type="string", example="Contenu du commentaire"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="video_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commentaire non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Le Commentaire avec l'identifiant {id} n'existe pas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erreur interne du serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Erreur lors de la récupération du Commentaire")
     *         )
     *     )
     * )
     */
    public function show(string $id)
    {
        $commentaires = Commentaire::find($id);

        if (!$commentaires) {
            return response()->json([
                "Message" => "Le Commentaire avec l'identifiant $id n'existe pas."
            ], 404);
        }

        return response()->json([
            "Message" => "Tous les Commentaires",
            "Commentaires" => new CommentaireResource($commentaires)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $newCommentaire = Commentaire::find($id);

    //     if (!$newCommentaire) {
    //         return response()->json([
    //             "Message" => "Le Commentaire avec l'identifiant $id n'existe pas."
    //         ], 404);
    //     }

    //     $this->authorize('update', $newCommentaire);

    //     $newCommentaire->contenu = $request->contenu;

    //     if ($newCommentaire->save()) {
    //         return response()->json([
    //             "Message" => "Modification d'un Commentaire Réussi",
    //             "Nouvelles Informations" => new CommentaireResource($newCommentaire)
    //         ]);
    //     }
    //     return response()->json([
    //         "Message" => "Modification d'un commentaire Echoué"
    //     ]);
    // }
    /**
     * @OA\Post(
     *     path="/api/commentaires/{id}",
     *     summary="Mise à jour d'un commentaire",
     *     description="Met à jour les informations d'un commentaire en fonction de son identifiant.",
     *     tags={"Commentaire"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant du commentaire à mettre à jour",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Nouvelles informations du commentaire",
     *         @OA\JsonContent(
     *             @OA\Property(property="contenu", type="string", example="Nouveau contenu du commentaire", description="Nouveau contenu du commentaire")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commentaire mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Modification d'un Commentaire Réussie"),
     *             @OA\Property(property="Nouvelles Informations", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="contenu", type="string", example="Nouveau contenu du commentaire"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="video_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commentaire non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Le Commentaire avec l'identifiant {id} n'existe pas.")
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
     *             @OA\Property(property="Erreur", type="string", example="Modification du Commentaire Échouée")
     *         )
     *     )
     * )
     */
    public function update(UpdateCommentaire $request, string $id)
    {
        $newCommentaire = Commentaire::find($id);

        if (!$newCommentaire) {
            return response()->json([
                "Message" => "Le Commentaire avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $this->authorize('update', $newCommentaire);

        $newCommentaire->contenu = $request->input('contenu');

        if ($newCommentaire->save()) {
            return response()->json([
                "Message" => "Modification d'un Commentaire Réussi",
                "Nouvelles Informations" => new CommentaireResource($newCommentaire)
            ]);
        }

        return response()->json([
            "Message" => "Modification d'un commentaire Échouée"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/commentaires/{id}",
     *     summary="Suppression d'un commentaire",
     *     description="Supprime un commentaire en fonction de son identifiant.",
     *     tags={"Commentaire"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant du commentaire à supprimer",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Commentaire supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Le commentaire a été supprimé avec succès"),
     *             @OA\Property(property="Commentaire Supprimé", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="contenu", type="string", example="Contenu du commentaire"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="video_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Commentaire non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Le Commentaire avec l'identifiant {id} n'existe pas.")
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
     *         response=500,
     *         description="Erreur interne du serveur",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Échec de la suppression du Commentaire")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $commentaire = Commentaire::find($id);

        if (!$commentaire) {
            return response()->json([
                "Message" => "Le Commentaire avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $this->authorize('update', $commentaire);

        if ($commentaire->delete()) {
            return response()->json([
                "Message" => "Le commentaire a etait supprimer avec Success",
                "Commentaire Supprimé" => new CommentaireResource($commentaire)
            ]);
        };
    }
}
