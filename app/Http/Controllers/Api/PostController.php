<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest\ShowPost;
use App\Http\Requests\PostRequest\StorePost;
use App\Http\Requests\PostRequest\UpdatePost;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\Cast\String_;

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
    public function store(StorePost $request)
    {
        $this->authorize('create', Post::class);

        // Validation des champs requis
        $request->validated();

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
        $newPost->user_id = $request->user()->id;

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
        $post = Post::find($id);

        if (!$post) {
            return response()->json([
                "Message" => "Le post avec l'identifiant $id n'existe pas."
            ], 404);
        }

        return response()->json([
            "Message" => "Affichage d'un Post",
            "Information du Post" => new PostResource($post)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePost $request, String $id)
    {
        $post = Post::findOrFail($id);
        $this->authorize('update', $post);

        // Validation des champs requis
        $request->validate([
            'titre' => 'required|string|max:255',
            'path_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contenu' => 'required|string',
        ]);

        // Mise à jour des champs du post
        $post->titre = $request->titre;
        $post->contenu = $request->contenu;

        // Mise à jour de l'image si un nouveau fichier est fourni
        if ($request->hasFile('path_image')) {
            // Supprimer l'ancienne image si elle existe
            Storage::disk('public')->delete($post->image);

            // Enregistrer la nouvelle image
            $fileName = time() . "." . $request->path_image->extension();
            $image_path = $request->path_image->storeAs(
                'images_posts',
                $fileName,
                'public'
            );

            $post->image = $image_path;
        }

        // Sauvegarde des modifications
        if ($post->save()) {
            return response()->json([
                "Message" => "Post mis à jour avec succès !",
                "Information du Post" => new PostResource($post)
            ], 200);
        }

        return response("Mise à jour du Post échouée");
    }



    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        $post = Post::find($id);
        $this->authorize('delete', $post);


        if (!$post) {
            return response()->json([
                "Message" => "Le post avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $this->authorize('delete', $post);

        $post->delete();

        return response()->json([
            "Message" => "Supprimer un Post",
            "Post" => new PostResource($post)
        ]);
    }
}
