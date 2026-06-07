<?php

declare(strict_types=1);

namespace App\Presentation\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

final class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:100'],
            'email' => ['required', 'email:rfc', 'max:255'],
            'password' => ['required', 'string', 'min:10', 'confirmed'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Le nom est requis.',
            'email.required' => "L'adresse email est requise.",
            'email.email' => "Format d'email invalide.",
            'password.required' => 'Le mot de passe est requis.',
            'password.min' => 'Le mot de passe doit contenir au moins :min caracteres.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ];
    }
}
