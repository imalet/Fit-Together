<?php

namespace App\Http\Requests\RoleRequest;

use Illuminate\Foundation\Http\FormRequest;

class RoleUpdate extends FormRequest
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
            'role' => 'required|string|max:255|unique:roles,role,' . $this->route('id'),
        ];
    }

    public function messages()
    {
        return [
            'role.required' => 'Le champ role est requis.',
            'role.string' => 'Le champ role doit être une chaîne de caractères.',
            'role.max' => 'Le champ role ne doit pas dépasser :max caractères.',
            'role.unique' => 'Ce rôle existe déjà.',
        ];
    }
}
