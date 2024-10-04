<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UserStoreRequest extends FormRequest
{
//    public mixed $name;
//    public mixed $email;
//    public mixed $cargo;
//    public mixed $identificacion;
//    public mixed $celular;
//    public mixed $fecha_nacimiento;
//    public mixed $role;

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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:' . User::class,
            // 'email' => 'required|string|email|max:255|unique:' . User::class,
            // 'password' => ['required', 'confirmed', Password::defaults()],
             'role' => ['required'],

            'identificacion' => 'required|Integer|min:1000|unique:' . User::class,
            'sexo' => 'nullable',
            'fecha_nacimiento' => 'nullable',
            'celular' => 'nullable',

            'cargo' => 'required',
            'tipo_user' => 'nullable',
            'firma' => 'nullable',
        ];
    }
}
