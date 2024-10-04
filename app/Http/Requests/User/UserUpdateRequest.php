<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        $id = $this->route('user');
        return [
            'name' => 'required|string|max:255',
            'email' => [
            'required',
            'string',
            'max:255',
            Rule::unique('users')->ignore($id),
            ],
            'identificacion' => [
                'required',
                'integer',
                'min:1000',
                Rule::unique('users')->ignore($id),
            ],

            'role' => ['required'],

            'sexo' => 'nullable',
            'fecha_nacimiento' => 'nullable',
            'celular' => 'nullable',

            'cargo' => 'required',
            'tipo_user' => 'nullable',
            'firma' => 'nullable',
        ];
    }
}
