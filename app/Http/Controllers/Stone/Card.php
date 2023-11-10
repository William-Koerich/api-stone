<?php

namespace App\Http\Controllers\Stone;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class Card
{
    public function create($request)
    {

        try {

            $client = new Client();
            $api = env('BASE_URL').'customers/'.$request->cliente_id.'/cards';

            $body = json_encode([
                'number' => $request['numero'],
                'holder_name' => $request['nome'],
                'holder_document' => $request['documento_cliente'],
                'exp_month' => $request['exp_mes'],
                'exp_year' => $request['exp_ano'],
                'cvv' => $request['cvv'],
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

            Log::channel('Customer')->critical('Erro ao criar um cartão', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar cartão, verificar com o suporte',
            ], 500);
        }

    }

    public function search($request)
    {
        try {

            $client = new Client();
            $api = env('BASE_URL').'customers/'.$request->cliente_id.'/cards';

            return $client->request('GET', $api, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                    'content-type' => 'application/json',
                ],
            ]);

        } catch (GuzzleException $e) {

            Log::channel('Customer')->critical('Erro ao buscar um cartão', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cartão, verificar com o suporte',
            ], 500);
        }
    }

    public function edit($request)
    {
        try {

            $client = new Client();
            $api = env('BASE_URL').'customers/'.$request->cliente_id.'/cards/'.$request->cartao_id;

            $body = [
                'holder_name' => $request['nome'],
                'exp_month' => $request['exp_mes'],
                'exp_year' => $request['exp_ano'],
            ];

            return $client->request('PUT', $api, [
                'headers' => [
                    'body' => $body,
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                    'content-type' => 'application/json',
                ],
            ]);

        } catch (GuzzleException $e) {

            Log::channel('Customer')->critical('Erro ao editar um cartão', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao editar cartão, verificar com o suporte',
            ], 500);
        }
    }

    public function delete($request)
    {
        try {

            $client = new Client();
            $api = env('BASE_URL').'customers/'.$request->cliente_id.'/cards/'.$request->cartao_id;

            return $client->request('DELETE', $api, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                    'content-type' => 'application/json',
                ],
            ]);

        } catch (GuzzleException $e) {

            Log::channel('Customer')->critical('Erro ao deletar um cartão', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao deletar cartão, verificar com o suporte',
            ], 500);
        }
    }

    public function searchOne($request)
    {
        try {

            $client = new Client();
            $api = env('BASE_URL').'customers/'.$request->cliente_id.'/cards/'.$request->cartao_id;

            return $client->request('GET', $api, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                    'content-type' => 'application/json',
                ],
            ]);

        } catch (GuzzleException $e) {

            Log::channel('Customer')->critical('Erro ao buscar um cartão', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cartão, verificar com o suporte',
            ], 500);
        }
    }

    public function createToken($request)
    {

        try {
            $client = new \GuzzleHttp\Client();

            $api = env('BASE_URL').'tokens';

            $body = [
                'type' => 'card',
                'card' => [
                    'number' => $request['numero'],
                    'holder_name' => $request['nome'],
                    'exp_month' => $request['exp_mes'],
                    'exp_year' => $request['exp_ano'],
                    'cvv' => $request['cvv'],
                ],
            ];

            $response = $client->request('POST', $api, [
                'body' => $body,
                'headers' => [
                    'accept' => 'application/json',
                    'content-type' => 'application/json',
                ],
            ]);
        } catch (GuzzleException $e) {

            Log::channel('Customer')->critical('Erro ao criar um token do cartão', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar token cartão, verificar com o suporte',
            ], 500);
        }
    }
}
