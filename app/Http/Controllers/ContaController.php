<?php

namespace App\Http\Controllers;

use App\Models\Conta;
use App\Models\Extrato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContaController extends Controller
{
    public function deposito(Request $request)
    {
        DB::beginTransaction();
        $conta = Conta::whereNumero($request->conta)->first();
//        $conta = Conta::lockForUpdate()->whereNumero($request->conta)->first();
        if (!$conta) {
            DB::rollBack();
            return response()->json([
                'error' => 'Conta não encontrada',
            ], 404);
        }

        $conta->saldo += $request->valor;
        $conta->save();

        $data = [
            'operacao' => 'deposito',
            'conta_origem_id' => $conta->id,
            'conta_destino_id' => null,
            'valor' => $request->valor,
        ];

        $extrato = Extrato::create($data);
        if (!$extrato) {
            DB::rollBack();
            return response()->json([
                'error' => 'Erro ao criar extrato',
            ], 422);
        }
        DB::commit();

        return response()->json(['conta' => $conta, 'extrato' => $extrato]);
    }

    public function saldo(Request $request)
    {
        DB::beginTransaction();
        $conta = Conta::whereNumero($request->conta)->with('extratos')->first();
        if (!$conta) {
            DB::rollBack();
            return response()->json([
                'error' => 'Conta não encontrada',
            ], 404);
        }
        DB::commit();

        return response()->json(['conta' => $conta]);
    }

    public function saque(Request $request)
    {
        DB::beginTransaction();
        $conta = Conta::whereNumero($request->conta)->first();
//        $conta = Conta::lockForUpdate()->whereNumero($request->conta)->first();
        if (!$conta) {
            DB::rollBack();
            return response()->json([
                'error' => 'Conta não encontrada',
            ], 404);
        }

        // os saques so serao realizados se houver saldo disponivel. nao existe saldo negativo.
        if ($conta->saldo < $request->valor) {
            DB::rollBack();
            return response()->json([
                'error' => 'Saldo insuficiente',
            ], 422);
        }

        sleep(15);

        $conta->saldo -= $request->valor;
        $conta->save();

        $data = [
            'operacao' => 'saque',
            'conta_origem_id' => $conta->id,
            'conta_destino_id' => null,
            'valor' => $request->valor,
        ];

        $extrato = Extrato::create($data);
        if (!$extrato) {
            DB::rollBack();
            return response()->json([
                'error' => 'Erro ao criar extrato',
            ], 422);
        }
        DB::commit();

        return response()->json(['conta' => $conta, 'extrato' => $extrato]);
    }

    public function transferir(Request $request)
    {
        DB::beginTransaction();
        $contaOrigem = Conta::whereNumero($request->conta_origem)->first();
//        $contaOrigem = Conta::lockForUpdate()->whereNumero($request->conta_origem)->first();

        $contaDestino = Conta::whereNumero($request->conta_destino)->first();
//        $contaDestino = Conta::lockForUpdate()->whereNumero($request->conta_destino)->first();
        if (!$contaOrigem || !$contaDestino) {
            DB::rollBack();
            return response()->json([
                'error' => 'Conta não encontrada',
            ], 404);
        }

        // as transferencias so serao realizados se houver saldo disponivel. nao existe saldo negativo.
        if ($contaOrigem->saldo < $request->valor) {
            DB::rollBack();
            return response()->json([
                'error' => 'Saldo insuficiente',
            ], 422);
        }

        $contaOrigem->saldo -= $request->valor;
        $contaOrigem->save();

        $contaDestino->saldo += $request->valor;
        $contaDestino->save();

        $data = [
            'operacao' => 'transferencia-saida',
            'conta_origem_id' => $contaOrigem->id,
            'conta_destino_id' => $contaDestino->id,
            'valor' => $request->valor,
        ];

        $extrato = Extrato::create($data);
        if (!$extrato) {
            DB::rollBack();
            return response()->json([
                'error' => 'Erro ao criar extrato',
            ], 422);
        }

        $data = [
            'operacao' => 'transferencia-entrada',
            'conta_origem_id' => $contaDestino->id,
            'conta_destino_id' => $contaOrigem->id,
            'valor' => $request->valor,
        ];

        $extrato = Extrato::create($data);
        if (!$extrato) {
            DB::rollBack();
            return response()->json([
                'error' => 'Erro ao criar extrato',
            ], 422);
        }

        DB::commit();

        return response()->json(['conta_origem' => $contaOrigem, 'extrato' => $contaOrigem->extratos()->first()]);
    }
}
