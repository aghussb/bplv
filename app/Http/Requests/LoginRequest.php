<?php 

namespace App\Http\Requests;

use Laravel\Fortify\Http\Requests\LoginRequest as FortifyLoginRequest;
use Laravel\Fortify\Fortify;

class LoginRequest extends FortifyLoginRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        
        return [
            Fortify::username() => 'required|string',
            'password' => 'required|string',
        ];
    }

    /**
     * @return array|string[]
     */
    public function messages()
    {
        return [
            Fortify::username().'.required' => ':attribute belum diisi.',
            'password.required' => ':attribute belum diisi.',
        ];
    }

}

?>