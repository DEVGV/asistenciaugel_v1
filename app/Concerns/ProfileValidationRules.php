<?php

namespace App\Concerns;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

trait ProfileValidationRules
{
    /**
     * Get the validation rules used to validate user profiles.
     *
     * @return array<string, array<int, ValidationRule|array<mixed>|string>>
     */
    protected function profileRules(?int $userId = null): array
    {
        return [
            'login' => $this->loginRules($userId),
        ];
    }

    /**
     * Get the validation rules used to validate user login (documento).
     *
     * @return array<int, ValidationRule|array<mixed>|string>
     */
    protected function loginRules(?int $userId = null): array
    {
        return [
            'required',
            'string',
            'max:20',
            $userId === null
                ? Rule::unique(User::class, 'login')
                : Rule::unique(User::class, 'login')->ignore($userId),
        ];
    }
}
