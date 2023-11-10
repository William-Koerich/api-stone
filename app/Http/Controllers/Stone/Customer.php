<?php

namespace App\Http\Controllers\Stone;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

class Customer
{
    public function generate(mixed $request): JsonResponse|ResponseInterface
    {

        try {

            $client = new Client();
            $api = env('BASE_URL').'customers';

            $body = json_encode([
                'birthdate' => $request['nascimento'],
                'name' => $request['nome'],
                'email' => $request['email'],
                'code' => $request['code'],
                'document' => $request['documento'],
                'document_type' => $request['tipo_documento'],
                'type' => $request['tipo_cliente'],
                'gender' => $request['genero'],
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

            Log::channel('Customer')->critical('Erro ao criar um cliente', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar cliente, verificar com o suporte',
            ], 500);
        }
    }

    public function search(string $id): ResponseInterface|JsonResponse
    {
        try {

            $client = new Client();
            $api = env('BASE_URL').'customers/'.$id;

            return $client->request('GET', $api, [
                'headers' => [
                    'accept' => 'application/json',
                    'authorization' => env('API_TOKEN'),
                    'content-type' => 'application/json',
                ],
            ]);

        } catch (GuzzleException $e) {

            Log::channel('Customer')->critical('Erro ao buscar um cliente', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            if ($e->getCode() != 404) {
                return response()->json([
                    'status' => 'error',
                    'code' => '500',
                    'message' => 'Ocorreu um erro ao buscar um cliente, verifique com o suporte!',
                ], 500);
            }

            return response()->json([
                'status' => 'error',
                'code' => '404',
                'message' => 'Cliente não encontrado!',
            ], 404);

        }
    }

    public function edit($request)
    {
        try {

            $client = new Client();
            $api = env('BASE_URL').'customers/'.$request['cliente_id'];

            $body = json_encode([
                'name' => $request['nome'],
                'email' => $request['email'],
                'code' => $request['codigo'],
                'document' => $request['documento'],
                'type_document' => $request['tipo_documento'],
                'type' => $request['tipo_cliente'],
                'gender' => $request['genero'],
                'birthdate' => $request['data_nascimento'],
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

            Log::channel('Customer')->critical('Erro ao editar um cliente', [
                'exception' => $e->getMessage(),
                'code' => $e->getCode(),
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Ocorreu um erro ao editar um cliente, verifique com o suporte!',
            ], 500);

        }
    }
}
