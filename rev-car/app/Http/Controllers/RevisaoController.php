<?php

namespace App\Http\Controllers;

use App\Models\Revisao;
use Illuminate\Http\Request;

class RevisaoController extends Controller
{
    public function index()
    {
        return Revisao::with('veiculo')->get();
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'veiculo_id' => 'required|exists:veiculos,id',
            'data_revisao' => 'required|date',
            'descricao' => 'required|string',
        ]);

        $revisao = Revisao::create($dados);

        return response()->json($revisao, 201);
    }

    public function show($id)
    {
        return Revisao::with('veiculo')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $revisao = Revisao::findOrFail($id);

        $dados = $request->validate([
            'veiculo_id' => 'exists:veiculos,id',
            'data_revisao' => 'date',
            'descricao' => 'string',
        ]);

        $revisao->update($dados);

        return response()->json($revisao);
    }

    public function destroy($id)
    {
        Revisao::destroy($id);
        return response()->json(['mensagem' => 'Revisão removida com sucesso!']);
    }
}