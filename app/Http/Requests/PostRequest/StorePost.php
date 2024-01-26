<?php

namespace App\Http\Requests\PostRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePost extends FormRequest
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
    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'path_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contenu' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'titre.required' => 'Le champ titre est requis.',
            'titre.string' => 'Le titre doit être une chaîne de caractères.',
            'titre.max' => 'Le titre ne doit pas dépasser 255 caractères.',
            'path_image.required' => 'Le champ image est requis.',
            'path_image.image' => 'Le fichier doit être une image.',
            'path_image.mimes' => 'L\'image doit être de type : jpeg, png, jpg, gif.',
            'path_image.max' => 'La taille de l\'image ne doit pas dépasser 2048 kilo-octets.',
            'contenu.required' => 'Le champ contenu est requis.',
            'contenu.string' => 'Le contenu doit être une chaîne de caractères.',
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
