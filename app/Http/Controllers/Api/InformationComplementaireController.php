<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\InformationComplementaireRequest\StoreInformationComplementaire;
use App\Http\Requests\InformationComplementaireRequest\UpdateInformationComplementaire;
use App\Http\Resources\InformationCompleteResource;
use App\Models\InformationComplementaire;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Http\Request;

class InformationComplementaireController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $informationComplementaires = InformationComplementaire::all();

        return response()->json([
            "Message" => "Lister Tous les Users",
            "Users" => InformationCompleteResource::collection($informationComplementaires)
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
    // public function store(Request $request)
    // {

    //     $this->authorize('create', InformationComplementaire::class);

    //     $informationComplementaire = new InformationComplementaire();
    //     $informationComplementaire->user_id = $request->user()->id;
    //     $informationComplementaire->bio = $request->bio;
    //     $informationComplementaire->qualification = $request->qualification;
    //     $informationComplementaire->experience = $request->experience;

    //     if ($informationComplementaire->save()) {
    //         return response()->json([
    //             "Message" => "Information Complementaires Ajouté avec Success !",
    //             "Information Complementaire" => new InformationCompleteResource($informationComplementaire)
    //         ], 200);
    //     }
    //     return response("Ajout d'Information Complementaires Echoué !");
    // }

    public function store(StoreInformationComplementaire $request)
    {
        $this->authorize('create', InformationComplementaire::class);

        $informationComplementaire = new InformationComplementaire();
        $informationComplementaire->user_id = $request->user()->id;
        $informationComplementaire->bio = $request->input('bio');
        $informationComplementaire->qualification = $request->input('qualification');
        $informationComplementaire->experience = $request->input('experience');

        if ($informationComplementaire->save()) {
            return response()->json([
                "Message" => "Informations Complementaires Ajoutées avec Succès !",
                "Information Complementaire" => new InformationCompleteResource($informationComplementaire)
            ], 200);
        }

        return response("Ajout d'Informations Complementaires Échoué !");
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $informationComplementaire = InformationComplementaire::find($id);

        if (!$informationComplementaire) {
            return response()->json([
                "Message" => "L'information Complementaire avec l'identifiant $id n'existe pas."
            ], 404);
        }

        return response()->json([
            "Message" => "Affichage d'une Video",
            "Information de la Video" => new InformationCompleteResource($informationComplementaire)
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

    //     $informationComplementaire = InformationComplementaire::findOrFail($id);

    //     $this->authorize('update', $informationComplementaire);

    //     $informationComplementaire->bio = $request->bio;
    //     $informationComplementaire->qualification = $request->qualification;
    //     $informationComplementaire->experience = $request->experience;

    //     if ($informationComplementaire->save()) {
    //         return response()->json([
    //             "Message" => "Information Complementaires Modifié avec Success !",
    //             "Information Complementaires" => new InformationCompleteResource($informationComplementaire)
    //         ], 200);
    //     }
    //     return response("Moficication d'Information Complementaires Echoué !");
    // }

    public function update(UpdateInformationComplementaire $request, string $id)
    {
        $informationComplementaire = InformationComplementaire::find($id);

        $this->authorize('update', $informationComplementaire);

        $informationComplementaire->bio = $request->input('bio');
        $informationComplementaire->qualification = $request->input('qualification');
        $informationComplementaire->experience = $request->input('experience');

        if ($informationComplementaire->save()) {
            return response()->json([
                "Message" => "Informations Complementaires Modifiées avec Succès !",
                "Information Complementaires" => new InformationCompleteResource($informationComplementaire)
            ], 200);
        }

        return response("Modification d'Informations Complementaires Échouée !");
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $informationComplementaire = InformationComplementaire::find($id);

        if (!$informationComplementaire) {
            return response()->json([
                "Message" => "L'information Complementaire avec l'identifiant $id n'existe pas."
            ], 404);
        }

        $this->authorize('delete', $informationComplementaire);

        if (!$informationComplementaire) {
            return response("Desole, l'Information Complementaire que vous essayez de supprimer n'existe pas !");
        }

        $informationComplementaire->delete();

        return response()->json([
            "Message" => "Supprimer une Information Complementaire",
            "Information Complementaire" => new InformationCompleteResource($informationComplementaire)
        ]);
    }
}
