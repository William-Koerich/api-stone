<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Stone\Plan;
use App\Http\Requests\PlanRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class PlanController extends Controller
{
    /**
     * @throws InternalErrorException
     */
    public function create(PlanRequest $request): array|JsonResponse
    {
        $plan = new Plan();
        $responsePlan = $plan->generate($request);

        if ($responsePlan->getStatusCode() != 200) {

            Log::channel('Plan')->critical('Erro ao criar um plano', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar um plano, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar um plano, verificar com o suporte',
            ], 500);
        }

        Log::channel('Plan')->critical('Sucesso ao criar um plano', [
            'request' => $request->all(),
            'response' => $responsePlan->getBody(),
            'code' => $responsePlan->getStatusCode(),
        ]);

        return [
            'plano' => json_decode($responsePlan->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Plano gerado com sucesso!',
        ];
    }

    public function edit(Request $request): JsonResponse|array
    {
        $plan = new Plan();
        $responsePlan = $plan->edit($request);

        if ($responsePlan->getStatusCode() != 200) {

            Log::channel('Plan')->critical('Erro ao editar um plano', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao editar um plano, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao editar um plano, verificar com o suporte',
            ], 500);
        }

        Log::channel('Plan')->critical('Sucesso ao editar um plano', [
            'request' => $request->all(),
            'response' => $responsePlan->getBody(),
            'code' => $responsePlan->getStatusCode(),
        ]);

        return [
            'plano' => json_decode($responsePlan->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Plano editado com sucesso!',
        ];

    }

    public function delete(Request $request)
    {
        $plan = new Plan();
        $responsePlan = $plan->delete($request);

        if ($responsePlan->getStatusCode() != 200) {

            Log::channel('Plan')->critical('Erro ao excluir um plano', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao excluir um plano, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao excluir um plano, verificar com o suporte',
            ], 500);
        }

        Log::channel('Plan')->critical('Sucesso ao excluir um plano', [
            'request' => $request->all(),
            'response' => $responsePlan->getBody(),
            'code' => $responsePlan->getStatusCode(),
        ]);

        return [
            'plano' => json_decode($responsePlan->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Plano excluido com sucesso!',
        ];
    }
}
