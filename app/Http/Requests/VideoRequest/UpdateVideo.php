<?php

namespace App\Http\Requests\VideoRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateVideo extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        return [
            'titre' => 'required|string|max:255',
            'path_video' => 'nullable|mimes:mp4,mov,avi|max:20480', // Exemple avec des règles pour les vidéos
            'duree' => 'required|integer|min:1',
            'sous_categorie_id' => 'required|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'titre.required' => 'Le champ titre est requis.',
            'titre.string' => 'Le titre doit être une chaîne de caractères.',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'path_video.mimes' => 'La vidéo doit être de type : mp4, mov, avi.',
            'path_video.max' => 'La taille de la vidéo ne doit pas dépasser 20480 kilo-octets.',
            'duree.required' => 'Le champ durée est requis.',
            'duree.integer' => 'La durée doit être un nombre entier.',
            'duree.min' => 'La durée doit être d\'au moins 1 minute.',
            'categorie_id.required' => 'Le champ catégorie est requis.',
            'categorie_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
