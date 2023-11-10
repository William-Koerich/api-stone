<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'cliente.nome' => 'required|string',
            'cliente.email' => 'required|string',
            'cartao.nome' => 'required|string',
            'cartao.numero' => 'required|string',
            'cartao.exp_mes' => 'required|string',
            'cartao.exp_ano' => 'required|string',
            'cartao.cvv' => 'required|string',
            'installments' => 'required|string',
            'plano_id' => 'required|string',
            'metodo_pagamento' => 'required|string',
            'cliente_id' => 'required|string',
            'codigo' => 'required|string',
        ];
    }
}
