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
    public function index()
    {
        $createurId = 5;
        
        // $videoRegarde = VideoRegarde::find(1);
        // $video = $videoRegarde->video->user_id;
        // $createurVideo = $videoRegarde->user->nom;

        $videosRegardees = VideoRegarde::whereHas('video', function ($query) use ($createurId) {
            $query->where('user_id', $createurId);
        })->get();


        return response()->json([
            "Message" => "Lister Toutes les videos",
            "Videos" => VideoRegardeResource::collection($videosRegardees)
        ]);
    }

    // public function index()
    // {
    //     $videos = VideoRegarde::all();

    //     return response()->json([
    //         "Message" => "Lister Toutes les videos",
    //         "Videos" => VideoRegardeResource::collection($videos)
    //     ]);
    // }

    /**
     * Store a newly created resource in storage.
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
