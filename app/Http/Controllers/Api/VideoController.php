<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VideoResource;
use App\Models\User;
use App\Models\Video;
use Dotenv\Repository\RepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Video::class);

        $fileName = time() . "." . $request->path_video->extension();

        $video_path = $request->path_video->storeAs(
            'videos_posts',
            $fileName,
            'public'
        );

        $newVideo = new Video();
        $newVideo->titre = $request->titre;
        $newVideo->path_video = $video_path;
        $newVideo->duree = $request->duree;
        $newVideo->user_id = $request->user()->id;
        $newVideo->categorie_id = $request->categorie_id;

        if ($newVideo->save()) {
            return response()->json([
                "Message" => "Video Ajouté avec Success !",
                "Vidéo Ajouté" => new VideoResource($newVideo)
            ], 200);
        }
        return response("Video Ajoué avec Success !");
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $video = Video::findOrFail($id);

        return response()->json([
            "Message" => "Affichage d'une Video",
            "Information de la Video" => new VideoResource($video)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $video = Video::findOrFail($id);

    //     $this->authorize('update', Video::class);


    //     $video->titre = $request->titre;
    //     $video->path_video = $request->path_video;
    //     $video->duree = $request->duree;
    //     $video->update();

    //     return response()->json([
    //         "Message" => "Modifier une Video",
    //         "Nouvelle Informations" => new VideoResource($video)
    //     ]);
    // }
    public function update(Request $request, string $id)
    {
        $video = Video::findOrFail($id);

        $this->authorize('update', $video);

        $video->titre = $request->titre;
        $video->path_video = $request->path_video;
        $video->duree = $request->duree;
        $video->update();

        return response()->json([
            "Message" => "Modifier une Video",
            "Nouvelle Informations" => new VideoResource($video)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        
        $video = Video::findOrFail($id);
        $this->authorize('delete', $video);

        if (!$video) {
            return response("Desole, la video que vous essayez de supprimer n'existe pas !");
        }

        $video->delete();

        return response()->json([
            "Message" => "Supprimer une Video",
            "Video" => new VideoResource($video)
        ]);
    }
}
