<?php

namespace App\Http\Requests\CommentaireRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCommentaire extends FormRequest
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
            'contenu' => 'required|string',
            'video_id' => 'required|exists:videos,id',
        ];
    }

    public function messages()
    {
        return [
            'contenu.required' => 'Le champ contenu est requis.',
            'contenu.string' => 'Le contenu doit être une chaîne de caractères.',
            'video_id.required' => 'Le champ video_id est requis.',
            'video_id.exists' => 'La vidéo sélectionnée n\'existe pas.',
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
