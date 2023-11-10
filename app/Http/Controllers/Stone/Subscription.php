<?php

namespace App\Http\Controllers\Stone;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class Subscription
{
    public function generate(mixed $request): ResponseInterface|JsonResponse
    {
        try {
            $client = new Client();
            $api = env('BASE_URL').'subscriptions';

            $customer = new Customer();

            $cliente = $customer->search($request['cliente_id']);

            if ($cliente->getStatusCode() == 404) {
                return response()->json([
                    'status' => 'error',
                    'code' => '404',
                    'message' => 'Cliente fornecido não foi encontrado',
                ], 404);
            }

            $body = json_encode([
                'customer' => [
                    'name' => $request['cliente']['nome'],
                    'email' => $request['cliente']['email'],
                ],
                'card' => [
                    'number' => $request['cartao']['numero'],
                    'holder_name' => $request['cartao']['nome'],
                    'exp_month' => $request['cartao']['exp_mes'],
                    'exp_year' => $request['cartao']['exp_ano'],
                    'cvv' => $request['cartao']['cvv'],
                ],
                'installments' => $request['installments'],
                'plan_id' => $request['plano_id'],
                'payment_method' => $request['metodo_pagamento'],
                'customer_id' => $request['cliente_id'],
                'code' => $request['codigo'],
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

            Log::channel('Subscription')->critical('Erro ao gerar uma assinatura', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao gerar assinatura, verificar com o suporte',
            ], 500);
        }
    }

    public function cancel($request): JsonResponse|ResponseInterface
    {
        try {
            $client = new Client();

            $body = json_encode([
                'cancel_pending_invoices' => $request['cancelar_cobrancas_pendentes'],
            ]);

            return $client->request('DELETE', env('BASE_URL').'subscriptions/'.$request['assinatura_id'], [
                'body' => $body,
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                    'content-type' => 'application/json',
                ],
            ]);

        } catch (GuzzleException $e) {

            Log::channel('Subscription')->critical('Erro ao cancelar uma assinatura', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao cancelar assinatura, verificar com o suporte',
            ], 500);
        }

    }

    public function search($request): JsonResponse|ResponseInterface
    {
        try {

            $client = new Client();

            $filter = [
                'status' => $request['status'],
                'code' => $request['codigo_assinatura'],
                'billing_type' => $request['tipo_cobranca'],
                'customer_id' => $request['cliente_id'],
                'plan_id' => $request['plano_id'],
                'created_since' => $request['data_inicio_periodo_criacao'],
                'created_until' => $request['data_final_periodo_criacao'],
            ];

            $filterFormated = array_filter($filter, fn ($value) => ! is_null($value) && $value !== '');

            $body = http_build_query($filterFormated);

            $url = env('BASE_URL').'subscriptions?'.$body;

            return $client->request('GET', $url, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                ],

            ]);

        } catch (GuzzleException $e) {

            Log::channel('Subscription')->critical('Erro ao buscar assinatura', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar assinatura, verificar com o suporte',
            ], 500);
        }
    }

    public function planless($request)
    {
        try {
            $client = new Client();
            $api = env('BASE_URL').'subscriptions';

            $customer = new Customer();

            $cliente = $customer->search($request['cliente_id']);

            if ($cliente->getStatusCode() == 404) {
                return response()->json([
                    'status' => 'error',
                    'code' => '404',
                    'message' => 'Cliente fornecido não foi encontrado',
                ], 404);
            }

            $body = json_encode(
                [
                    'payment_method' => $request['metodo_pagamento'],
                    'interval' => 'month',
                    'minimum_price' => 100,
                    'interval_count' => 1,
                    'billing_type' => 'prepaid',
                    'installments' => $request['installments'],
                    'card' => [
                        'number' => $request['cartao']['numero'],
                        'holder_name' => $request['cartao']['nome'],
                        'exp_month' => $request['cartao']['exp_mes'],
                        'exp_year' => $request['cartao']['exp_ano'],
                        'cvv' => $request['cartao']['cvv'],
                    ],

                    'pricing_scheme' => [
                        'scheme_type' => $request['preco']['tipo'],
                        'price' => $request['preco']['valor'],
                        'minimum_price' => 100,
                    ],

                    'quantity' => 1,
                    'code' => $request['codigo'],
                    'currency' => 'BRL',
                    'customer_id' => $request['cliente_id'],
                    'description' => $request['descricao'],
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

            Log::channel('Subscription')->critical('Erro ao criar assinatura sem plano', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar assinatura sem plano, verificar com o suporte',
            ], 500);
        }
    }
}
