<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Stone\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function create(Request $request): JsonResponse|array
    {
        $order = new Order();
        $response = $order->create($request);

        if ($response['responseOrder']->charges[0]->last_transaction->gateway_response->code != 200 && $response['responseCharge']->last_transaction->gateway_response->code != 200) {

            Log::channel('Order')->critical('Erro ao criar um pedido', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar um pedido, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar um pedido, verificar com o suporte',
            ], 500);
        }

        Log::channel('Order')->critical('Sucesso ao criar um pedido', [
            'request' => $request->all(),
            'response' => [
                'order' => $response['responseOrder'],
                'charge' => $response['responseCharge'],
            ],
        ]);

        return [
            'pedido' => $response['responseOrder'],
            'cobranca' => $response['responseCharge'],
            'status' => 'success',
            'code' => '200',
            'message' => 'Pedido gerado com sucesso!',
        ];
    }

    public function charge(Request $request): JsonResponse|array
    {
        $order = new Order();
        $responseOrder = $order->charge($request);

        if ($responseOrder->getStatusCode() != 200) {

            Log::channel('Order')->critical('Erro ao incluir cobrança ao pedido', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao incluir cobrança ao pedido, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao incluir cobrança ao pedido, verificar com o suporte',
            ], 500);
        } elseif ($responseOrder->getStatusCode() == 200 && json_decode($responseOrder->getBody())->last_transaction->gateway_response->code != 200) {

            Log::channel('Order')->critical('Erro ao cobrar o pedido', [
                'request' => $request->all(),
                'response' => json_decode($responseOrder->getBody()),
                'code' => json_decode($responseOrder->getBody())->last_transaction->gateway_response->code,
            ]);

            return response()->json([
                'status' => 'error',
                'code' => json_decode($responseOrder->getBody())->last_transaction->gateway_response->code,
                'message' => json_decode($responseOrder->getBody())->last_transaction->gateway_response->errors,
            ], 500);
        }

        Log::channel('Order')->critical('Sucesso ao incluir cobrança ao pedido', [
            'request' => $request->all(),
            'response' => $responseOrder->getBody(),
            'code' => $responseOrder->getStatusCode(),
        ]);

        return [
            'pedido' => json_decode($responseOrder->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Cobrança gerada com sucesso!',
        ];
    }
}
