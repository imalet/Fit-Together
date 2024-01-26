<?php

namespace App\Http\Requests\InformationComplementaireRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreInformationComplementaire extends FormRequest
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
            'bio' => 'required|string',
            'qualification' => 'required|string',
            'experience' => 'required|string',
        ];
    }

    public function messages()
    {
        return [
            'bio.required' => 'Le champ bio est requis.',
            'bio.string' => 'Le champ bio doit être une chaîne de caractères.',
            'qualification.required' => 'Le champ qualification est requis.',
            'qualification.string' => 'Le champ qualification doit être une chaîne de caractères.',
            'experience.required' => 'Le champ experience est requis.',
            'experience.string' => 'Le champ experience doit être une chaîne de caractères.',
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
