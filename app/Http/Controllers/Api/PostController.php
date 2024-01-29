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
    /**
     * @OA\Get(
     *     path="/api/posts",
     *     summary="Liste tous les posts",
     *     description="Récupère la liste de tous les posts disponibles.",
     *     tags={"Post"},
     *     @OA\Response(
     *         response=200,
     *         description="Liste des posts récupérée avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Lister Tous les posts"),
     *             @OA\Property(property="Posts", type="array", @OA\Items(
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="title", type="string", example="Titre du post"),
     *                 @OA\Property(property="content", type="string", example="Contenu du post"),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             ))
     *         )
     *     )
     * )
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
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/post",
     *     summary="Ajout d'un nouveau post",
     *     description="Ajoute un nouveau post avec les informations fournies.",
     *     tags={"Post"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Informations du nouveau post",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="titre", type="string", example="Titre du post"),
     *                 @OA\Property(property="path_image", type="string", format="binary", description="Chemin de l'image du post"),
     *                 @OA\Property(property="contenu", type="string", example="Contenu du post")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post ajouté avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Post Ajouté avec Succès !"),
     *             @OA\Property(property="Information du Post", type="object",
     *                 @OA\Property(property="titre", type="string", example="Titre du post"),
     *                 @OA\Property(property="image", type="string", example="Chemin de l'image du post"),
     *                 @OA\Property(property="contenu", type="string", example="Contenu du post"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
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
     *             @OA\Property(property="Erreur", type="string", example="Ajout de Post Échoué")
     *         )
     *     )
     * )
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
    /**
     * @OA\Get(
     *     path="/api/post/{id}",
     *     summary="Affichage d'un post",
     *     description="Affiche les informations détaillées d'un post en fonction de son identifiant.",
     *     tags={"Post"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant du post",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Informations du post affichées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Affichage d'un Post"),
     *             @OA\Property(property="Information du Post", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titre", type="string", example="Titre du post"),
     *                 @OA\Property(property="image", type="string", example="Chemin de l'image du post"),
     *                 @OA\Property(property="contenu", type="string", example="Contenu du post"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-01-29T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-01-29T12:30:00Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Post non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Le post avec l'identifiant {id} n'existe pas.")
     *         )
     *     )
     * )
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
    /**
     * @OA\Post(
     *     path="/api/post/{id}",
     *     summary="Mise à jour d'un post",
     *     description="Met à jour les informations d'un post en fonction de son identifiant.",
     *     tags={"Post"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant du post",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Nouvelles informations du post",
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="titre", type="string", example="Nouveau Titre du post"),
     *                 @OA\Property(property="path_image", type="string", format="binary", description="Nouveau chemin de l'image du post"),
     *                 @OA\Property(property="contenu", type="string", example="Nouveau Contenu du post")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Post mis à jour avec succès !"),
     *             @OA\Property(property="Information du Post", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titre", type="string", example="Nouveau Titre du post"),
     *                 @OA\Property(property="image", type="string", example="Nouveau Chemin de l'image du post"),
     *                 @OA\Property(property="contenu", type="string", example="Nouveau Contenu du post"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
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
     *         response=404,
     *         description="Post non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Le post avec l'identifiant {id} n'existe pas.")
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
     *             @OA\Property(property="Erreur", type="string", example="Mise à jour du Post échouée")
     *         )
     *     )
     * )
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

    /**
     * @OA\Delete(
     *     path="/api/post/{id}",
     *     summary="Suppression d'un post",
     *     description="Supprime un post en fonction de son identifiant.",
     *     tags={"Post"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identifiant du post",
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Post supprimé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Suppression d'un Post"),
     *             @OA\Property(property="Post", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="titre", type="string", example="Titre du post"),
     *                 @OA\Property(property="image", type="string", example="Chemin de l'image du post"),
     *                 @OA\Property(property="contenu", type="string", example="Contenu du post"),
     *                 @OA\Property(property="user_id", type="integer", example=1),
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
     *         response=404,
     *         description="Post non trouvé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Message", type="string", example="Le post avec l'identifiant {id} n'existe pas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Non autorisé",
     *         @OA\JsonContent(
     *             @OA\Property(property="Erreur", type="string", example="Vous n'avez pas le droit de supprimer ce post.")
     *         )
     *     )
     * )
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
