<?php

namespace App\Http\Controllers;

use App\Models\Veiculo;
use Illuminate\Http\Request;

class VeiculoController extends Controller
{
    public function index()
    {
        return Veiculo::with(['proprietario', 'revisoes'])->get();
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'marca' => 'required|string|max:255',
            'modelo' => 'required|string|max:255',
            'placa' => 'required|string|max:10|unique:veiculos,placa',
            'proprietario_id' => 'required|exists:proprietarios,id',
        ]);

        $veiculo = Veiculo::create($dados);

        return response()->json($veiculo, 201);
    }

    public function show($id)
    {
        return Veiculo::with(['proprietario', 'revisoes'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $veiculo = Veiculo::findOrFail($id);

        $dados = $request->validate([
            'marca' => 'string|max:255',
            'modelo' => 'string|max:255',
            'placa' => 'string|max:10|unique:veiculos,placa,' . $id,
            'proprietario_id' => 'exists:proprietarios,id',
        ]);

        $veiculo->update($dados);

        return response()->json($veiculo);
    }

    public function destroy($id)
    {
        Veiculo::destroy($id);
        return response()->json(['mensagem' => 'Veículo removido com sucesso!']);
    }
}