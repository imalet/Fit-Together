<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\SousCategorieRequest\StoreSousCategorie;
use App\Http\Requests\SousCategorieRequest\UpdateSousCategorie;
use App\Http\Resources\SousCategorieResource;
use App\Models\SousCategorie;
use Illuminate\Http\Request;

class SousCategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sousCategories = SousCategorie::all();

        return response()->json(['data' => SousCategorieResource::collection($sousCategories)]);
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
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'sous_categorie' => 'required',
    //         'categorie_id' => 'required|exists:categories,id',
    //     ]);

    //     $sousCategorie = new SousCategorie();
    //     $sousCategorie->sous_categorie = $request->sous_categorie;
    //     $sousCategorie->categorie_id = $request->categorie_id;
    //     $sousCategorie->save();

    //     return response()->json(['message' => 'Sous-categorie created successfully', 'data' => new SousCategorieResource($sousCategorie)], 201);
    // }

    public function store(StoreSousCategorie $request)
    {
        $this->authorize('create',SousCategorie::class);

        $sousCategorie = new SousCategorie();
        $sousCategorie->sous_categorie = $request->sous_categorie;
        $sousCategorie->categorie_id = $request->categorie_id;
        $sousCategorie->save();

        return response()->json([
            'message' => 'Sous-categorie créée avec succès',
            'data' => new SousCategorieResource($sousCategorie)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        $sousCategorie = SousCategorie::find($id);

        if (!$sousCategorie) {
            return response()->json([
                "Message" => "L'information Complementaire avec l'identifiant $id n'existe pas."
            ], 404);
        }

        return response()->json(['data' => new SousCategorieResource($sousCategorie)]);
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
    // public function update(Request $request, String $id)
    // {
    //     $sousCategorie = SousCategorie::find($id);

    //     $request->validate([
    //         'sous_categorie' => 'required',
    //         'categorie_id' => 'required|exists:categories,id',
    //     ]);

    //     $sousCategorie->sous_categorie = $request->sous_categorie;
    //     $sousCategorie->categorie_id = $request->categorie_id;
    //     $sousCategorie->update();

    //     return response()->json(['message' => 'Sous-categorie updated successfully', 'data' => new SousCategorieResource($sousCategorie)]);
    // }
    public function update(UpdateSousCategorie $request, string $id)
    {
        $sousCategorie = SousCategorie::find($id);

        if (!$sousCategorie) {
            return response()->json([
                "Message" => "La sous-catégorie avec l'identifiant $id n'existe pas."
            ], 404);
        }
        $this->authorize('update', $sousCategorie);

        
        $sousCategorie->sous_categorie = $request->input('sous_categorie');
        $sousCategorie->categorie_id = $request->input('categorie_id');
        $sousCategorie->save();

        return response()->json([
            'message' => 'Sous-categorie mise à jour avec succès',
            'data' => new SousCategorieResource($sousCategorie)
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $sousCategorie = SousCategorie::find($id);

        $this->authorize('delete', $sousCategorie);

        if (!$sousCategorie) {
            return response()->json([
                "Message" => "L'information Complementaire avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $sousCategorie->delete();

        return response()->json(['message' => 'Sous-categorie deleted successfully', 'Data' => new SousCategorieResource($sousCategorie)]);
    }
}
