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
