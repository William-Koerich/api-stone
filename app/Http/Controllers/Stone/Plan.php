<?php

namespace App\Http\Controllers\Stone;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class Plan
{
    public function generate($request): JsonResponse|ResponseInterface
    {
        try {

            $client = new Client();

            $api = env('BASE_URL').'plans';

            $body = json_encode([
                'interval' => 'month',
                'interval_count' => 1,
                'pricing_scheme' => [
                    'scheme_type' => 'Unit',
                    'price' => $request->preco,
                ],
                'quantity' => 1,
                'name' => $request->nome,
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

            Log::channel('Subscription')->critical('Erro ao gerar um plano', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao gerar um plano, verificar com o suporte',
            ], 500);

        }
    }

    public function edit($request): JsonResponse|ResponseInterface
    {
        try {

            $client = new Client();

            $api = env('BASE_URL').'plans/'.$request['plano_id'];

            $body = json_encode([
                'name' => $request['nome'],
                'status' => $request['status'],
                'minimum_price' => $request['preco'],
                'currency' => 'BRL',
                'interval' => 'month',
                'interval_count' => 1,
                'payment_methods' => ['credit_card'],
                'billing_type' => 'exact_day',
                'billing_days' => $request['dia_pagamento'],
            ]);

            return $client->request('PUT', $api, [
                'body' => $body,
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                    'content-type' => 'application/json',
                ],
            ]);

        } catch (GuzzleException $e) {

            Log::channel('Subscription')->critical('Erro ao editar um plano', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao editar um plano, verificar com o suporte',
            ], 500);

        }
    }

    public function delete($request)
    {
        try {

            $client = new Client();

            $api = env('BASE_URL').'plans/'.$request['plano_id'];

            return $client->request('DELETE', $api, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                    'content-type' => 'application/json',
                ],
            ]);

        } catch (GuzzleException $e) {

            Log::channel('Plan')->critical('Erro ao exluir um plano', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao exluir um plano, verificar com o suporte',
            ], 500);

        }
    }
}
