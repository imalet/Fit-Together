<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRegardeRequest\StoreVideoRegarde;
use App\Http\Resources\VideoRegardeResource;
use App\Models\VideoRegarde;
use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VideoRegardeController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     *     path="/api/videoRegardes",
     *     summary="Liste toutes les vidéos",
     *     description="Récupère la liste de toutes les vidéos disponibles.",
     *     tags={"Video Regardé"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste de vidéos récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Lister Toutes les vidéos"),
     *             @OA\Property(property="Videos", type="array",
     *                 @OA\Items(type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="titre", type="string", example="Titre de la vidéo"),
     *                     @OA\Property(property="url", type="string", example="URL de la vidéo"),
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
     *             @OA\Property(property="Erreur", type="string", example="Erreur lors de la récupération de la liste des vidéos")
     *         )
     *     )
     * )
     */
    public function index()
    {
        $videos = VideoRegarde::all();

        return response()->json([
            "Message" => "Lister Toutes les videos",
            "Videos" => VideoRegardeResource::collection($videos)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/videoRegarde",
     *     summary="Ajout d'une vidéo à regarder",
     *     description="Ajoute une vidéo à la liste des vidéos à regarder.",
     *     tags={"Video Regardé"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Informations de la vidéo à ajouter",
     *         @OA\JsonContent(
     *             @OA\Property(property="user_id", type="integer", example=1, description="Identifiant de l'utilisateur"),
     *             @OA\Property(property="video_id", type="integer", example=1, description="Identifiant de la vidéo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vidéo ajoutée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Insertion Réussie !"),
     *             @OA\Property(property="Vidéo ajoutée", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
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
     *             @OA\Property(property="Erreur", type="string", example="Insertion de la vidéo échouée")
     *         )
     *     )
     * )
     */
    public function store(StoreVideoRegarde $request)
    {
        $this->authorize('create', VideoRegarde::class);

        $newVideoRegarde = new VideoRegarde();
        $newVideoRegarde->user_id = $request->input('user_id');
        $newVideoRegarde->video_id = $request->input('video_id');

        if ($newVideoRegarde->save()) {
            return response()->json([
                "Message" => "Insertion Réussie !",
                "Vidéo ajoutée" => new VideoRegardeResource($newVideoRegarde)
            ]);
        }

        return response()->json([
            "Erreur" => "Insertion Échouée !"
        ]);
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/videoRegarde/{id}",
     *     summary="Affichage d'une vidéo regardée",
     *     description="Affiche les informations détaillées d'une vidéo regardée en fonction de son identifiant.",
     *     tags={"Video Regardé"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la vidéo regardée",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informations de la vidéo regardée affichées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Affichage d'une Video"),
     *             @OA\Property(property="Information de la Video Regardé", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="video_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Video Regardé non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Une Video Regardé avec l'identifiant {id} n'existe pas.")
     *         )
     *     )
     * )
     */
    public function show(String $id)
    {
        $videoRegarde = VideoRegarde::find($id);


        if (!$videoRegarde) {
            return response()->json([
                "Message" => "Une Video Regardé avec l'identifiant $id n'existe pas."
            ], 404);
        }

        return response()->json([
            "Message" => "Affichage d'une Video",
            "Information de la Video Regardé" => new VideoRegardeResource($videoRegarde)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        return response("Update Video Regarde");
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/video/{id}",
     *     summary="Suppression d'une vidéo regardée",
     *     description="Supprime une vidéo regardée en fonction de son identifiant.",
     *     tags={"Video Regardé"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la vidéo regardée",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vidéo supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Supprimer une Video"),
     *             @OA\Property(property="Vidéo Supprimé", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="video_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Video Regardé non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Une Video Regardé avec l'identifiant {id} n'existe pas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Vous n'avez pas le droit de supprimer cette vidéo.")
     *         )
     *     )
     * )
     */
    public function destroy(string $id)
    {

        $videoRegarde = VideoRegarde::find($id);

        if (!$videoRegarde) {
            return response()->json([
                "Message" => "Une Vide Regarde avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $videoRegarde->delete();

        return response()->json([
            "Message" => "Supprimer une Video",
            "Vidéo Supprimé" => new VideoRegardeResource($videoRegarde)


        ]);
    }
}
