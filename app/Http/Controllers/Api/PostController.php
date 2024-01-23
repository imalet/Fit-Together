<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
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
            "Posts" => PostResource::collection($posts)
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

        $newPost = new Post();
        $newPost->titre = $request->titre;
        $newPost->image = $image_path;
        $newPost->contenu = $request->contenu;
        $newPost->user_id = $request->user_id;

        if ($newPost->save()) {
            return response()->json([
                "Message" => "Post Ajoué avec Success !",
                "Information du Post" => new PostResource($newPost)
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
            "Information du Post" => new PostResource($post)
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
            "Post" => new PostResource($post)
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
            "Post" => new PostResource($post)
        ]);
    }
}
