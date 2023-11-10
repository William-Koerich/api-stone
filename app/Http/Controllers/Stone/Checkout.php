<?php

namespace App\Http\Controllers\Stone;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class Checkout
{
    public function create($request)
    {
        try {

            $client = new Client();

            $api = env('BASE_URL').'orders';

            $body = json_encode([
                'customer_id' => $request['cliente_id'],
                'closed' => false,
                'items' => [
                    [
                        'amount' => $request['items']['valor'],
                        'description' => $request['items']['descricao'],
                        'quantity' => $request['items']['quantidade'],
                        'code' => $request['items']['codigo'],
                    ],

                ],
                'payments' => [
                    [
                        'payment_method' => 'checkout',
                        'checkout' => [
                            'customer_editable' => false,
                            'skip_checkout_success_page' => true,
                            'accepted_payment_methods' => ['credit_card'],
                            'success_url' => 'https://www.pagar.me',
                            'credit_card' => [
                                'capture' => true,
                                'statement_descriptor' => 'Desc na fatura',
                                'installments' => [
                                    [
                                        'number' => 1,
                                        'total' => 2000,
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            return $client->request('POST', $api, [
                'body' => $body,
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                    'content-type' => 'application/json',
                ],
            ]);

        } catch (GuzzleException $e) {
            Log::channel('Order')->critical('Erro ao gerar um pedido', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao gerar um pedido, verificar com o suporte',
            ], 500);

        }
    }
}
