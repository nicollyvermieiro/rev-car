<?php

namespace App\Http\Controllers;

use App\Models\Proprietario;
use Illuminate\Http\Request;

class ProprietarioController extends Controller
{
    public function index()
    {
        return Proprietario::with('veiculos')->get();
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'sexo' => 'required|in:M,F',
            'data_nascimento' => 'required|date',
            'email' => 'required|email|unique:proprietarios,email',
        ]);

        $proprietario = Proprietario::create($dados);

        return response()->json($proprietario, 201);
    }

    public function show($id)
    {
        return Proprietario::with('veiculos')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $proprietario = Proprietario::findOrFail($id);

        $dados = $request->validate([
            'nome' => 'string|max:255',
            'sexo' => 'in:M,F',
            'data_nascimento' => 'date',
            'email' => 'email|unique:proprietarios,email,' . $id,
        ]);

        $proprietario->update($dados);

        return response()->json($proprietario);
    }

    public function destroy($id)
    {
        Proprietario::destroy($id);
        return response()->json(['mensagem' => 'Proprietário removido com sucesso!']);
    }
}