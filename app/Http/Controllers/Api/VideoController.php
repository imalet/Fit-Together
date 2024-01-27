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
    public function index()
    {
        $videos = Video::all();

        return response()->json([
            "Message" => "Lister Toutes les videos",
            "Videos" => VideoResource::collection($videos)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */


    /**
     * Store a newly created resource in storage.
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
