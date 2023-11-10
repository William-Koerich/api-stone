<?php

namespace App\Http\Controllers\Stone;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class Charge
{
    public function search($request)
    {
        try {

            $client = new Client();

            $filter = [
                'status' => $request['status'],
                'code' => $request['codigo'],
                'payment_method' => $request['metodo_pagamento'],
                'customer_id' => $request['cliente_id'],
                'order_id' => $request['pedido_id'],
                'created_since' => $request['data_inicio_periodo_criacao'],
                'created_until' => $request['data_final_periodo_criacao'],
            ];

            $filterFormated = array_filter($filter, fn ($value) => ! is_null($value) && $value !== '');

            $body = http_build_query($filterFormated);

            $url = env('BASE_URL').'charges?'.$body;

            return $client->request('GET', $url, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                ],

            ]);

        } catch (GuzzleException $e) {

            Log::channel('Charge')->critical('Erro ao buscar cobrança', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cobrança, verificar com o suporte',
            ], 500);
        }
    }

    public function cancel($request)
    {
        try {
            $client = new Client();

            return $client->request('DELETE', env('BASE_URL').'charges/'.$request['cobranca_id'], [
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

    public function retry($request)
    {
        try {

            $client = new Client();

            $body = json_encode([
                'charge_id' => $request['cobranca_id'],
            ]);

            return $client->request('POST', env('BASE_URL').'charges/'.$request['cobranca_id'].'/retry', [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                    'content-type' => 'application/json',
                ],
            ]);

        } catch (GuzzleException $e) {

            Log::channel('Charge')->critical('Erro ao reprocessar cobrança', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao reprocessar cobrança, verificar com o suporte',
            ], 500);
        }
    }
}
