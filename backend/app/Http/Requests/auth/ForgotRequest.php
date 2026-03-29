<?php


namespace App\Http\Requests\auth;


use Illuminate\Foundation\Http\FormRequest;

class ForgotRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:users,email'
        ];
    }
}