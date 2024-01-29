<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\VideoRequest\StoreVideo;
use App\Http\Requests\VideoRequest\UpdateVideo;
use App\Http\Resources\VideoResource;
use App\Models\User;
use App\Models\Video;
use Dotenv\Repository\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    // public function index()
    // {
    //     $videos = Video::all();

    //     return response()->json([
    //         "Message" => "Lister Toutes les videos",
    //         "Videos" => VideoResource::collection($videos)
    //     ]);
    // }
    /**
     * @OA\Get(
     *     path="/api/videos",
     *     summary="Liste de toutes les vidéos",
     *     description="Récupère la liste de toutes les vidéos disponibles.",
     *     tags={"Vidéos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Liste de toutes les vidéos",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Lister Toutes les vidéos"),
     *             @OA\Property(property="Videos", type="array",
     *                 @OA\Items(
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=1),
     *                     @OA\Property(property="titre", type="string", example="Titre de la vidéo"),
     *                     @OA\Property(property="url", type="string", example="https://exemple.com/video/1"),
     *                     @OA\Property(property="categorie_id", type="integer", example=1),
     *                     @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                     @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *                 )
     *             )
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
    public function index()
    {
        $videos = Video::all();

        return response()->json([
            "Message" => "Lister Toutes les vidéos",
            "Videos" => VideoResource::collection($videos)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/video",
     *     summary="Ajouter une nouvelle vidéo",
     *     description="Ajoute une nouvelle vidéo à la base de données.",
     *     tags={"Vidéos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="titre", type="string", example="Titre de la vidéo"),
     *                 @OA\Property(property="path_video", type="file", format="binary", example="chemin/vers/la/video.mp4"),
     *                 @OA\Property(property="duree", type="integer", example=120),
     *                 @OA\Property(property="sous_categorie_id", type="integer", example=1),
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vidéo ajoutée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Vidéo ajoutée avec succès !"),
     *             @OA\Property(property="Vidéo ajoutée", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titre", type="string", example="Titre de la vidéo"),
     *                 @OA\Property(property="path_video", type="string", example="chemin/vers/la/video.mp4"),
     *                 @OA\Property(property="duree", type="integer", example=120),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="sous_categorie_id", type="integer", example=1),
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
     *             @OA\Property(property="Erreur", type="string", example="Les données fournies sont invalides.")
     *         )
     *     )
     * )
     */
    public function store(StoreVideo $request)
    {
        $this->authorize('create', Video::class);

        $fileName = time() . "." . $request->file('path_video')->extension();

        $video_path = $request->file('path_video')->storeAs(
            'videos_posts',
            $fileName,
            'public'
        );

        $newVideo = new Video();
        $newVideo->titre = $request->titre;
        $newVideo->path_video = $video_path;
        $newVideo->duree = $request->duree;
        $newVideo->user_id = $request->user()->id;
        $newVideo->sous_categorie_id = $request->sous_categorie_id;

        if ($newVideo->save()) {
            return response()->json([
                "Message" => "Vidéo ajoutée avec succès !",
                "Vidéo ajoutée" => new VideoResource($newVideo)
            ], 200);
        }

        return response("Ajout de la vidéo échoué");
    }

    /**
     * Display the specified resource.
     */
    /**
     * @OA\Get(
     *     path="/api/video/{id}",
     *     summary="Afficher les détails d'une vidéo",
     *     description="Récupère les détails d'une vidéo en fonction de son identifiant.",
     *     tags={"Vidéos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la vidéo",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Détails de la vidéo",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Affichage d'une Video"),
     *             @OA\Property(property="Information de la Video", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titre", type="string", example="Titre de la vidéo"),
     *                 @OA\Property(property="path_video", type="string", example="chemin/vers/la/video.mp4"),
     *                 @OA\Property(property="duree", type="integer", example=120),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="sous_categorie_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vidéo non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="La vidéo avec l'identifiant {id} n'existe pas.")
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
    public function show(String $id)
    {
        $video = Video::find($id);

        if (!$video) {
            return response()->json([
                "Message" => "Le post avec l'identifiant $id n'existe pas."
            ], 404);
        }

        return response()->json([
            "Message" => "Affichage d'une Video",
            "Information de la Video" => new VideoResource($video)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $video = Video::findOrFail($id);

    //     $this->authorize('update', $video);

    //     $video->titre = $request->titre;
    //     $video->path_video = $request->path_video;
    //     $video->duree = $request->duree;
    //     $video->update();

    //     return response()->json([
    //         "Message" => "Modifier une Video",
    //         "Nouvelle Informations" => new VideoResource($video)
    //     ]);
    // }

    /**
     * @OA\Put(
     *     path="/api/videos/{id}",
     *     summary="Mettre à jour une vidéo",
     *     description="Met à jour les détails d'une vidéo en fonction de son identifiant.",
     *     tags={"Vidéos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la vidéo à mettre à jour",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Objet JSON contenant les nouvelles informations de la vidéo",
     *         @OA\JsonContent(
     *             @OA\Property(property="titre", type="string", example="Nouveau Titre de la vidéo"),
     *             @OA\Property(property="duree", type="integer", example=150),
     *             @OA\Property(property="sous_categorie_id", type="integer", example=2),
     *             @OA\Property(property="path_video", type="string", format="binary"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vidéo mise à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Vidéo mise à jour avec succès !"),
     *             @OA\Property(property="Information de la Vidéo", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titre", type="string", example="Nouveau Titre de la vidéo"),
     *                 @OA\Property(property="path_video", type="string", example="chemin/vers/la/nouvelle/video.mp4"),
     *                 @OA\Property(property="duree", type="integer", example=150),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="sous_categorie_id", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:45:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vidéo non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="La vidéo avec l'identifiant {id} n'existe pas.")
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
     *             @OA\Property(property="Message", type="string", example="Validation échouée"),
     *             @OA\Property(property="Erreurs", type="object",
     *                 @OA\Property(property="titre", type="array", @OA\Items(type="string", example="Erreur sur le titre")),
     *                 @OA\Property(property="duree", type="array", @OA\Items(type="string", example="Erreur sur la durée")),
     *                 @OA\Property(property="sous_categorie_id", type="array", @OA\Items(type="string", example="Erreur sur la sous-catégorie")),
     *                 @OA\Property(property="path_video", type="array", @OA\Items(type="string", example="Erreur sur la vidéo"))
     *             )
     *         )
     *     )
     * )
     */
    public function update(UpdateVideo $request, string $id)
    {
        $video = Video::find($id);

        $this->authorize('update', $video);

        // Validation des champs
        // $request->validate($request->rules(), $request->messages());

        // Mise à jour des champs de la vidéo
        $video->titre = $request->titre;
        $video->duree = $request->duree;
        $video->sous_categorie_id = $request->sous_categorie_id;

        // Mise à jour du chemin de la vidéo si un nouveau fichier est fourni
        if ($request->hasFile('path_video')) {
            // Supprimer l'ancienne vidéo si elle existe
            Storage::disk('public')->delete($video->path_video);

            // Enregistrer la nouvelle vidéo
            $fileName = time() . "." . $request->file('path_video')->extension();
            $video_path = $request->file('path_video')->storeAs(
                'videos_posts',
                $fileName,
                'public'
            );

            $video->path_video = $video_path;
        }

        // Sauvegarde des modifications
        if ($video->save()) {
            return response()->json([
                "Message" => "Vidéo mise à jour avec succès !",
                "Information de la Vidéo" => new VideoResource($video)
            ], 200);
        }

        return response("Mise à jour de la vidéo échouée");
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * @OA\Delete(
     *     path="/api/videos/{id}",
     *     summary="Supprimer une vidéo",
     *     description="Supprime une vidéo en fonction de son identifiant.",
     *     tags={"Vidéos"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant de la vidéo à supprimer",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Vidéo supprimée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Supprimer une Video"),
     *             @OA\Property(property="Video", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titre", type="string", example="Titre de la vidéo"),
     *                 @OA\Property(property="path_video", type="string", example="chemin/vers/la/video.mp4"),
     *                 @OA\Property(property="duree", type="integer", example=120),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="sous_categorie_id", type="integer", example=2),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:45:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Vidéo non trouvée",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="La vidéo avec l'identifiant {id} n'existe pas.")
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

        $video = Video::find($id);

        if (!$video) {
            return response()->json([
                "Message" => "Le post avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $this->authorize('delete', $video);

        $video->delete();

        return response()->json([
            "Message" => "Supprimer une Video",
            "Video" => new VideoResource($video)
        ]);
    }
}
