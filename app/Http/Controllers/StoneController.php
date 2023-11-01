<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Stone\Billing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class StoneController extends Controller
{
    public function createBilling(Request $request) {

        try {
            $billing = new Billing();
            $responseBilling = $billing->generate($request);

            if($responseBilling->code <= 201 ){
                Log::channel('billing')->info('Criação de cobrança efetuada com sucesso!', [
                    'ip' => $request->ip(),
                    'data' => $request->params,
                    'response' => [
                        'billing' => $responseBilling,
                    ],
                ]);

                return json_encode([
                    'billing' => $responseBilling,

                ]);
            }else{
                Log::channel('billing')->error('Erro ao criar cobrança!', [
                    'ip' => $request->ip(),
                    'data' => $request->params,
                    'response' => [
                        'billing' => $responseBilling,
                    ],
                ]);
            }
        } catch (\Exception $e) {
            Log::channel('billing')->critical('Fatal error!', [
                'ip' => $request->ip(),
                'data' => $request,
                'exception' => $e,
            ]);

            return $e->getMessage();
        }
    }
}
