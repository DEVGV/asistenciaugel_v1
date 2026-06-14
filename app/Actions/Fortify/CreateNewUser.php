<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            'trabajador_id' => ['required', 'integer', 'exists:t_trabajador,id'],
            'login' => ['required', 'string', 'max:20', 'unique:auth.users,login'],
            'password' => $this->passwordRules(),
        ])->validate();

        return User::create([
            'trabajador_id' => $input['trabajador_id'],
            'login' => $input['login'],
            'password' => $input['password'],
        ]);
    }
}
