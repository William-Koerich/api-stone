<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Stone\Charge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ChargeController extends Controller
{
    public function search(Request $request)
    {
        $charge = new Charge();
        $responseCharge = $charge->search($request);

        if ($responseCharge->getStatusCode() == 404) {
            return $responseCharge->getBody();
        }

        if ($responseCharge->getStatusCode() != 200) {

            Log::channel('Charge')->critical('Erro ao buscar uma cobrança', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cobrança, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cobrança, verificar com o suporte',
            ], 500);
        }

        return [
            'cliente' => json_decode($responseCharge->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Cliente encontrado com sucesso!',
        ];
    }

    public function cancel(Request $request)
    {
        $charge = new Charge();
        $responseCharge = $charge->cancel($request);

        if ($responseCharge->getStatusCode() != 200) {
            Log::channel('Charge')->critical('Erro ao cancelar cobrança', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao cancelar cobrança, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao cancelar cobrança, verificar com o suporte',
            ], 500);
        }

        Log::channel('Charge')->info('Sucesso ao cancelar uma cobrança', [
            'request' => $request->all(),
            'response' => $responseCharge->getBody(),
            'code' => $responseCharge->getStatusCode(),
        ]);

        return [
            'assinatura' => json_decode($responseCharge->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Assinatura cancelada com sucesso!',
        ];
    }

    public function retry(Request $request)
    {
        $charge = new Charge();
        $responseCharge = $charge->retry($request);

        if ($responseCharge->getStatusCode() != 200) {
            Log::channel('Charge')->critical('Erro ao reprocessar cobrança', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao reprocessar cobrança, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao reprocessar cobrança, verificar com o suporte',
            ], 500);
        }

        Log::channel('Charge')->info('Sucesso ao reprocessar uma cobrança', [
            'request' => $request->all(),
            'response' => $responseCharge->getBody(),
            'code' => $responseCharge->getStatusCode(),
        ]);

        return [
            'cobranca' => json_decode($responseCharge->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Cobrança reprocessada com sucesso!',
        ];
    }
}
