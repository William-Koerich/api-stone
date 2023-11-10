<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Stone\Checkout;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CheckoutController extends Controller
{
    public function create(Request $request): JsonResponse|array
    {
        $checkout = new Checkout();
        $responseCheckout = $checkout->create($request);

        if ($responseCheckout->getStatusCode() != 200) {

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
            'response' => $responseCheckout->getBody(),
            'code' => $responseCheckout->getStatusCode(),
        ]);

        return [
            'pedido' => json_decode($responseCheckout->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Pedido gerado com sucesso!',
        ];
    }
}
