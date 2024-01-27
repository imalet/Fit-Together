<?php

namespace App\Http\Requests\VideoRegardeRequest;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreVideoRegarde extends FormRequest
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
            'user_id' => 'required|exists:users,id',
            'video_id' => 'required|exists:videos,id',
        ];
    }

    public function messages()
    {
        return [
            'user_id.required' => 'Le champ user_id est requis.',
            'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas.',
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
