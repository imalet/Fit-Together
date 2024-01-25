<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
    public function store(Request $request)
    {
        $request->validate([
            'sous_categorie' => 'required',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        $sousCategorie = new SousCategorie();
        $sousCategorie->sous_categorie = $request->sous_categorie;
        $sousCategorie->categorie_id = $request->categorie_id;
        $sousCategorie->save();

        return response()->json(['message' => 'Sous-categorie created successfully', 'data' => new SousCategorieResource($sousCategorie)], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(SousCategorie $sousCategorie)
    {
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
    public function update(Request $request, SousCategorie $sousCategorie)
    {
        $request->validate([
            'sous_categorie' => 'required',
            'categorie_id' => 'required|exists:categories,id',
        ]);

        $sousCategorie->update($request->all());

        return response()->json(['message' => 'Sous-categorie updated successfully', 'data' => new SousCategorieResource($sousCategorie)]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SousCategorie $sousCategorie)
    {
        $sousCategorie->delete();

        return response()->json(['message' => 'Sous-categorie deleted successfully','Data' => new SousCategorieResource($sousCategorie)]);
    }
}
