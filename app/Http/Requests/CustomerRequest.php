<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'nome' => 'required|string',
            'email' => 'required|email',
            'documento' => 'required|numeric',
            'tipo_documento' => 'required|string',
            'nascimento' => 'required|string',
            'code' => 'required|string',
            'tipo_cliente' => 'required|string',
            'genero' => 'required|string',
        ];
    }
}
