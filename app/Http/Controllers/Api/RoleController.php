<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest\RoleStore;
use App\Http\Requests\RoleRequest\RoleUpdate;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();

        return response()->json([
            "Message" => "Liste de Tous les Roles",
            "Posts" => RoleResource::collection($roles)
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoleStore $request)
    {
        $newRole = new Role();
        $newRole->role = $request->role;

        if ($newRole->save()) {
            return response()->json([
                "Message" => "Rôle ajouté avec succès !",
                "Information du Rôle" => new RoleResource($newRole)
            ], 200);
        }

        return response("Ajout d'un Rôle Échoué");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                "Message" => "Le rôle avec l'identifiant $id n'existe pas."
            ], 404);
        }

        // Ajoutez la vérification de la politique d'autorisation si nécessaire
        // $this->authorize('view', $role);

        return response()->json([
            "Message" => "Affichage d'un rôle",
            "Information du Rôle" => new RoleResource($role)
        ]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(RoleUpdate $request, string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                "Message" => "Le rôle avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $role->role = $request->input('role');

        if ($role->save()) {
            return response()->json([
                "Message" => "Rôle modifié avec succès !",
                "Information du Rôle" => new RoleResource($role)
            ], 200);
        }

        return response("Modification du Rôle Échouée");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = Role::find($id);

        if (!$role) {
            return response()->json([
                "Message" => "Le rôle avec l'identifiant $id n'existe pas."
            ], 404);
        }

        // Ajoutez une vérification pour s'assurer que le rôle ne peut pas être supprimé s'il est associé à des utilisateurs, par exemple
        if ($role->users()->exists()) {
            return response()->json([
                "Message" => "Impossible de supprimer le rôle car il est associé à des utilisateurs."
            ], 422);
        }

        // Ajoutez la vérification de la politique d'autorisation si nécessaire

        $role->delete();

        return response()->json([
            "Message" => "Rôle supprimé avec succès.",
            "Information du Rôle" => new RoleResource($role)
        ]);
    }
}
