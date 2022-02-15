<?php

namespace App\Data\Requests\Auth;

use Illuminate\Http\Request;

/**
 * Class LoginRequest
 * @package App\Data\Requests\Auth
 * @param string $email
 * @param string $password
 */
class LoginRequest extends Request
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
    }

}
