<?php

namespace App\Http\Controllers\Stone;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class Order
{
    public function create($request): JsonResponse|array|ResponseInterface
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
                        'credit_card' => [
                            'card' => [
                                'number' => $request['pagamento']['cartao']['numero'],
                                'holder_name' => $request['pagamento']['cartao']['nome'],
                                'exp_month' => $request['pagamento']['cartao']['exp_mes'],
                                'exp_year' => $request['pagamento']['cartao']['exp_ano'],
                                'cvv' => $request['pagamento']['cartao']['cvv'],
                            ],
                            'installments' => $request['pagamento']['parcelas'],
                            'statement_descriptor' => $request['pagamento']['nome_fatura_cartao'],
                        ],
                        'payment_method' => $request['pagamento']['metodo_pagamento'],

                    ],
                ],
            ]);

            $responseApi = $client->request('POST', $api, [
                'body' => $body,
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                    'content-type' => 'application/json',
                ],
            ]);

            if ($responseApi->getStatusCode() == 200) {
                $responseApi = json_decode($responseApi->getBody());

                $charge = $this->charge([
                    'pedido_id' => $responseApi->id,
                    'valor' => $request['items']['valor'],
                    'pagamento' => $request['pagamento'],
                ]);

                return [
                    'responseOrder' => $responseApi,
                    'responseCharge' => json_decode($charge->getBody()),
                ];
            }

            return $responseApi;

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

    public function charge($request): JsonResponse|ResponseInterface
    {
        try {

            $client = new Client();

            $api = env('BASE_URL').'charges';

            $body = json_encode([
                'payment' => [
                    'credit_card' => [
                        'card' => [
                            'number' => $request['pagamento']['cartao']['numero'],
                            'holder_name' => $request['pagamento']['cartao']['nome'],
                            'exp_month' => $request['pagamento']['cartao']['exp_mes'],
                            'exp_year' => $request['pagamento']['cartao']['exp_ano'],
                            'cvv' => $request['pagamento']['cartao']['cvv'],
                        ],
                        'installments' => $request['pagamento']['parcelas'],
                        'statement_descriptor' => $request['pagamento']['nome_fatura_cartao'],
                    ],
                    'payment_method' => $request['pagamento']['metodo_pagamento'],
                ],
                'order_id' => $request['pedido_id'],
                'amount' => $request['valor'],
            ]);

            return $client->request('POST', $api, [
                'body' => $body,
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                ],
            ]);

        } catch (GuzzleException $e) {

            Log::channel('Order')->critical('Erro ao incluir uma cobrança ao pedido', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao incluir uma cobrança ao pedido, verificar com o suporte',
            ], 500);

        }
    }
}
