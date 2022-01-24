<?php

namespace App\Http\Requests;

use App\Rules\AlphaSpaces;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfile extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        $userId = Auth::id();
        return [
            'name' => [
                'required',
                'max:50',
                new AlphaSpaces(),
            ],
            'email' => [
                'required',
                Rule::unique('users')->ignore($userId),
                'email',
            ],
            'phone' => [
                'min: 9',
            ],
            'avatar' => [
                'nullable',
                'file',
                'image',
            ],
            'defaultAvatar' => [
                'nullable',
            ]
        ];
    }

    public function messages()
    {
        return [
            'email.unique' => 'Podany adres email jest zajęty',
            'name.max' => 'Przekroczono maksymalną ilość znaków: :max',
            'phone.min' => 'Numer telefonu musi posiadać conajmniej :min znaków'
        ];
    }
}
