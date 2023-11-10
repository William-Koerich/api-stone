<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Stone\Subscription;
use App\Http\Requests\SubscriptionRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SubscriptionsController extends Controller
{
    public function create(SubscriptionRequest $request): JsonResponse|array
    {

        $subscription = new Subscription();
        $responseSubscription = $subscription->generate($request);

        if ($responseSubscription->getStatusCode() != 200) {

            Log::channel('Subscription')->critical('Erro ao criar uma assinatura', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao gerar assinatura, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao gerar assinatura, verificar com o suporte',
            ], 500);
        }

        Log::channel('Subscription')->critical('Sucesso ao criar uma assinatura', [
            'request' => $request->all(),
            'response' => $responseSubscription->getBody(),
            'code' => $responseSubscription->getStatusCode(),
        ]);

        return [
            'assinatura' => json_decode($responseSubscription->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Assinatura gerada com sucesso!',
        ];

    }

    public function cancel(Request $request): JsonResponse|array
    {
        $subscription = new Subscription();
        $responseSubscription = $subscription->cancel($request);

        if ($responseSubscription->getStatusCode() != 200) {
            Log::channel('Subscription')->critical('Erro ao cancelar assinatura', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao cancelar assinatura, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao cancelar assinatura, verificar com o suporte',
            ], 500);
        }

        Log::channel('Subscription')->info('Sucesso ao cancelar uma assinatura', [
            'request' => $request->all(),
            'response' => $responseSubscription->getBody(),
            'code' => $responseSubscription->getStatusCode(),
        ]);

        return [
            'assinatura' => json_decode($responseSubscription->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Assinatura cancelada com sucesso!',
        ];
    }

    public function search(Request $request): JsonResponse|array
    {
        $subscription = new Subscription();
        $responseSubscription = $subscription->search($request);

        if ($responseSubscription->getStatusCode() != 200) {
            Log::channel('Subscription')->critical('Erro ao buscar assinatura', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar assinatura, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar assinatura, verificar com o suporte',
            ], 500);
        }

        if (json_decode($responseSubscription->getBody())->data === []) {
            Log::channel('Subscription')->info('Nenhuma assinatura encontrada com os filtros!', [
                'request' => $request->all(),
                'response' => $responseSubscription->getBody(),
                'code' => $responseSubscription->getStatusCode(),
            ]);

            return [
                'assinatura' => json_decode($responseSubscription->getBody()),
                'status' => 'success',
                'code' => '200',
                'message' => 'Nenhuma assinatura encontrada com os filtros!',
            ];
        }

        Log::channel('Subscription')->info('Sucesso ao buscar uma assinatura', [
            'request' => $request->all(),
            'response' => $responseSubscription->getBody(),
            'code' => $responseSubscription->getStatusCode(),
        ]);

        return [
            'assinatura' => json_decode($responseSubscription->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Assinatura encontrada com sucesso!',
        ];
    }

    public function planless(Request $request): JsonResponse|array
    {
        $subscription = new Subscription();
        $responseSubscription = $subscription->planless($request);

        if ($responseSubscription->getStatusCode() != 200) {
            Log::channel('Subscription')->critical('Erro ao criar assinatura sem plano', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar assinatura sem plano, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar assinatura sem plano, verificar com o suporte',
            ], 500);
        }

        Log::channel('Subscription')->info('Sucesso ao criar uma assinatura sem plano', [
            'request' => $request->all(),
            'response' => $responseSubscription->getBody(),
            'code' => $responseSubscription->getStatusCode(),
        ]);

        return [
            'assinatura' => json_decode($responseSubscription->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Assinatura sem plano criada com sucesso!',
        ];
    }
}
