<?php
namespace App\Http\Controllers\Stone;

use GuzzleHttp\Client;

class Billing {
    public function generate($request) {
        try {
            $client = new Client([
               'base_uri' => env("BASE_URL"),
                'auth' => [env('PAGARME_API_KEY'), '']
            ]);

            $jsonData = [
                'amount' => 1300, // Valor total da cobrança em centavos (R$ 13,00 neste exemplo)
                'payment_method' => 'credit_card',
                'card_number' => '4111111111111111', // Número do cartão de teste
                'card_cvv' => '123', // CVV de teste
                'card_expiration_date' => '1225', // Data de vencimento do cartão de teste (MM/YY)
                'card_holder_name' => 'Nome do Titular',
                'items' => [
                    'id' => '12345',
                    'title' => 'Produto 1',
                    'unit_price' => 500, // Valor unitário em centavos (R$ 5,00)
                    'quantity' => 2,
                    'tangible' => true, // Se o produto é tangível
                ],

            ];

            $response = $client->post('/transactions', [
                'json' => $jsonData,
            ]);

            return response()->json([
                'data' => json_decode($response->getBody()->getContents()),
                'code' => $response->getStatusCode()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
