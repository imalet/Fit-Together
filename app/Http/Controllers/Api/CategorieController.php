<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategorieResource;
use App\Models\Categorie;
use Illuminate\Http\Request;

class CategorieController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categorie::all();

        return CategorieResource::collection($categories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'categorie' => 'required',
        ]);

        $category = Categorie::create($request->all());

        return new CategorieResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show(Categorie $categorie)
    {
        return new CategorieResource($categorie);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Categorie $categorie)
    {
        $request->validate([
            'categorie' => 'required',
        ]);

        $categorie->categorie = $request->categorie;
        $categorie->update();

        return new CategorieResource($categorie);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Categorie $categorie)
    {
        $categorie->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
