<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Stone\Customer;
use App\Http\Requests\CustomerRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\CssSelector\Exception\InternalErrorException;

class CustomerController extends Controller
{
    private Customer $customer;

    public function __construct()
    {
        $this->customer = new Customer();
    }

    /**
     * @throws InternalErrorException
     */
    public function create(CustomerRequest $request): array|JsonResponse
    {
        $validated = $request->validated();
        $responseCustomer = $this->customer->generate($validated);

        if ($responseCustomer->getStatusCode() != 200) {

            Log::channel('Customer')->critical('Erro ao criar um cliente', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cliente, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cliente, verificar com o suporte',
            ], 500);
        }

        Log::channel('Customer')->info('Sucesso ao criar um cliente', [
            'request' => $request->all(),
            'response' => $responseCustomer->getBody(),
            'code' => $responseCustomer->getStatusCode(),
        ]);

        return [
            'cliente' => json_decode($responseCustomer->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Cliente gerado com sucesso!',
        ];

    }

    /**
     * @throws InternalErrorException
     */
    public function search(Request $request): array|JsonResponse
    {
        $responseCustomer = $this->customer->search($request->id);

        if ($responseCustomer->getStatusCode() == 404) {
            return $responseCustomer;
        }

        if ($responseCustomer->getStatusCode() != 200) {

            Log::channel('Customer')->critical('Erro ao buscar um cliente', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cliente, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cliente, verificar com o suporte',
            ], 500);
        }

        return [
            'cliente' => json_decode($responseCustomer->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Cliente encontrado com sucesso!',
        ];
    }

    public function edit(Request $request)
    {
        $customer = new Customer();
        $responseCustomer = $customer->edit($request);

        if ($responseCustomer->getStatusCode() != 200) {

            Log::channel('Customer')->critical('Erro ao editar um cliente', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao editar cliente, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao editar cliente, verificar com o suporte',
            ], 500);
        }

        return [
            'cliente' => json_decode($responseCustomer->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Cliente encontrado com sucesso!',
        ];
    }
}
