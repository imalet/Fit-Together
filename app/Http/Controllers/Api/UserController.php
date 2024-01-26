<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest\UpdateUser;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return response()->json([
            "Message" => "Lister Tous les Users",
            "Users" => UserResource::collection($users)
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                "Message" => "l'Utilisateur avec l'identifiant $id n'existe pas."
            ], 404);
        }

        return response()->json([
            "Message" => "Affichage d'un Utilisateur",
            "Information de l'Utilisateur" => new UserResource($user)
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
    //     $fileName = time() . "." . $request->photoProfil->extension();

    //     $image_path = $request->photoProfil->storeAs(
    //         'images_profil',
    //         $fileName,
    //         'public'
    //     );

    //     $newData = User::findOrFail($id);
    //     $newData->photoProfil = $image_path;
    //     $newData->nom = $request->nom;
    //     $newData->prenom = $request->prenom;
    //     $newData->email = $request->email;

    //     if ($newData->save()) {
    //         return response()->json([
    //             "Nouvel Donne de l'Utilisateur" => new UserResource($newData),
    //             "Message" => "Modification d'un Utilisateur Réussi !"
    //         ], 200);
    //     }
    // }

    public function update(UpdateUser $request, string $id)
    {
        $fileName = time() . "." . $request->file('photoProfil')->extension();

        $image_path = $request->file('photoProfil')->storeAs(
            'images_profil',
            $fileName,
            'public'
        );

        $newData = User::findOrFail($id);
        $newData->photoProfil = $image_path;
        $newData->nom = $request->nom;
        $newData->prenom = $request->prenom;
        $newData->email = $request->email;

        if ($newData->save()) {
            return response()->json([
                "Nouvelle Donnée de l'Utilisateur" => new UserResource($newData),
                "Message" => "Modification d'un Utilisateur Réussie !"
            ], 200);
        }

        return response("Modification de l'Utilisateur échouée");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);


        if (!$user) {
            return response()->json([
                "Message" => "Le post avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $this->authorize('delete', $user);

        $user->delete();

        return response()->json([
            "Message" => "Supprimer une Video",
            "Video" => new UserResource($user)
        ]);
    }
}
