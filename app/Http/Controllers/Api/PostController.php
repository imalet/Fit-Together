<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::all();

        return response()->json([
            "Message" => "Lister Tous les posts",
            "Posts" => $posts
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
        $fileName = time() . "." . $request->path_image->extension();

        $image_path = $request->path_image->storeAs(
            'images_posts',
            $fileName,
            'public'
        );

        $newVideo = new Post();
        $newVideo->titre = $request->titre;
        $newVideo->image = $image_path;
        $newVideo->contenu = $request->contenu;
        $newVideo->user_id = $request->user_id;

        if ($newVideo->save()) {
            return response()->json([
                "Message" => "Post Ajoué avec Success !"
            ], 200);
        }
        return response("Ajout de Post Echoué");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $post = Post::findOrFail($id);

        return response()->json([
            "Message" => "Affichage d'un Post",
            "Information du Post" => $post
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
    public function update(Request $request, string $id)
    {
        $post = Post::findOrFail($id);

        $post->titre = $request->titre;
        $post->image = $request->image;
        $post->contenu = $request->contenu;
        $post->update();

        return response()->json([
            "Message" => "Modifier une Post",
            "Nouvelle Informations" => $post
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                "Message" => "Desole, le post que vous essayez de supprimer n'existe pas !"
            ]);
        }

        $post->delete();
        
        return response()->json([
            "Message" => "Supprimer un Post",
            "Post" => $post
        ]);
    }
}
