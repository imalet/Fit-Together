<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRegister extends FormRequest
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
            'photoProfil' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->route('id'),
        ];
    }

    public function messages()
    {
        return [
            'photoProfil.image' => 'Le fichier doit être une image.',
            'photoProfil.mimes' => 'Le fichier doit être de type :values.',
            'photoProfil.max' => 'La taille du fichier ne doit pas dépasser :max kilo-octets.',
            'nom.required' => 'Le champ nom est obligatoire.',
            'nom.string' => 'Le champ nom doit être une chaîne de caractères.',
            'nom.max' => 'Le champ nom ne doit pas dépasser :max caractères.',
            'prenom.required' => 'Le champ prénom est obligatoire.',
            'prenom.string' => 'Le champ prénom doit être une chaîne de caractères.',
            'prenom.max' => 'Le champ prénom ne doit pas dépasser :max caractères.',
            'email.required' => 'Le champ email est obligatoire.',
            'email.email' => 'Le champ email doit être une adresse e-mail valide.',
            'email.unique' => 'Cette adresse e-mail est déjà utilisée par un autre utilisateur.',
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
