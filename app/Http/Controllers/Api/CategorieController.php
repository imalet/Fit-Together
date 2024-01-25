<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategorieRequest\StoreCategorie;
use App\Http\Requests\CategorieRequest\UpdateCategorie;
use App\Http\Resources\CategorieResource;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
    public function store(StoreCategorie $request)
    {
        $request->validated();

        $category = Categorie::create($request->all());

        return new CategorieResource($category);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $categorie = Categorie::findOrFail($id);
            return new CategorieResource($categorie);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Category not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategorie $request, Categorie $categorie)
    {
        $request->validated();

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
