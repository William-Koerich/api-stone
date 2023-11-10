<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Stone\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CardController extends Controller
{
    public function create(Request $request)
    {
        $card = new Card();
        $responseCard = $card->create();

        if ($responseCard->getStatusCode() != 200) {

            Log::channel('Card')->critical('Erro ao criar um cartão', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar cartão, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar cartão, verificar com o suporte',
            ], 500);
        }

        return [
            'cartao' => json_decode($responseCard->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Cartão criado com sucesso!',
        ];
    }

    public function search(Request $request)
    {
        $card = new Card();
        $responseCard = $card->search($request);

        if ($responseCard->getStatusCode() != 200) {

            Log::channel('Card')->critical('Erro ao buscar um cartão', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cartão, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cartão, verificar com o suporte',
            ], 500);
        }

        return [
            'cartao' => json_decode($responseCard->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Cartão encontrado com sucesso!',
        ];
    }

    public function edit(Request $request)
    {
        $card = new Card();
        $responseCard = $card->edit($request);

        if ($responseCard->getStatusCode() != 200) {

            Log::channel('Card')->critical('Erro ao editar um cartão', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao editar cartão, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao editar cartão, verificar com o suporte',
            ], 500);
        }

        return [
            'cartao' => json_decode($responseCard->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Cartão editado com sucesso!',
        ];
    }

    public function delete(Request $request)
    {
        $card = new Card();
        $responseCard = $card->delete($request);

        if ($responseCard->getStatusCode() != 200) {

            Log::channel('Customer')->critical('Erro ao excluir um cartão', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao excluir cartão, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao excluir cartão, verificar com o suporte',
            ], 500);
        }

        return [
            'cartao' => json_decode($responseCard->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Cartão excluido com sucesso!',
        ];
    }

    public function searchOne(Request $request)
    {
        $card = new Card();
        $responseCard = $card->searchOne($request);

        if ($responseCard->getStatusCode() != 200) {

            Log::channel('Card')->critical('Erro ao buscar um cartão', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cartão, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao buscar cartão, verificar com o suporte',
            ], 500);
        }

        return [
            'cartao' => json_decode($responseCard->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Cartão encontrado com sucesso!',
        ];
    }

    public function createToken(Request $request)
    {
        $card = new Card();
        $responseCard = $card->createToken($request);

        if ($responseCard->getStatusCode() != 200) {

            Log::channel('Card')->critical('Erro ao criar um token', [
                'request' => $request->all(),
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar token, verificar com o suporte',
            ]);

            return response()->json([
                'status' => 'error',
                'code' => '500',
                'message' => 'Erro ao criar token, verificar com o suporte',
            ], 500);
        }

        return [
            'token' => json_decode($responseCard->getBody()),
            'status' => 'success',
            'code' => '200',
            'message' => 'Token criado com sucesso!',
        ];
    }
}
