<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proprietario;
use App\Models\Veiculo;
use App\Models\Revisao;
use Illuminate\Support\Facades\DB;

class RelatoriosController extends Controller
{
    // Todos os veículos
    public function veiculos()
    {
        $dados = Veiculo::with('proprietario')->get()->toArray();
        return response()->json($dados);
    }

    // Veículos por pessoa
    public function veiculosPorPessoa()
    {
        $dados = Proprietario::with('veiculos')
            ->orderBy('nome')
            ->get()
            ->map(fn($p) => [
                'id' => $p->id,
                'nome' => $p->nome,
                'quantidade' => $p->veiculos->count(),
                'veiculos' => $p->veiculos->toArray(),
            ])
            ->toArray();

        return response()->json($dados);
    }

    // Veículos por sexo
    public function veiculosPorSexo()
    {
        $dados = Proprietario::select('sexo', DB::raw('count(veiculos.id) as total'))
            ->join('veiculos', 'proprietarios.id', '=', 'veiculos.proprietario_id')
            ->groupBy('sexo')
            ->orderByDesc('total')
            ->get()
            ->map(fn($d) => [
                'sexo' => $d->sexo ?? 'N/I',
                'total' => (int) $d->total
            ])
            ->toArray();

        return response()->json($dados);
    }

    // Marcas por quantidade
    public function marcasPorQuantidade()
    {
        $dados = Veiculo::select('marca', DB::raw('count(*) as quantidade'))
            ->groupBy('marca')
            ->orderByDesc('quantidade')
            ->get()
            ->map(fn($d) => [
                'marca' => $d->marca ?? 'Sem Marca',
                'quantidade' => (int) $d->quantidade
            ])
            ->toArray();

        return response()->json($dados);
    }

    // Marcas por sexo do proprietário
    public function marcasPorSexo()
    {
        $dados = Veiculo::join('proprietarios', 'veiculos.proprietario_id', '=', 'proprietarios.id')
            ->select('veiculos.marca', 'proprietarios.sexo', DB::raw('count(*) as total'))
            ->groupBy('veiculos.marca', 'proprietarios.sexo')
            ->orderByDesc('total')
            ->get()
            ->map(fn($d) => [
                'marca' => $d->marca ?? 'Sem Marca',
                'sexo' => $d->sexo ?? 'N/I',
                'total' => (int) $d->total
            ])
            ->toArray();

        return response()->json($dados);
    }

    // Todas as pessoas
    public function pessoas()
    {
        $dados = Proprietario::all()
            ->map(fn($p) => [
                'id' => $p->id,
                'nome' => $p->nome,
                'quantidade' => $p->veiculos->count() ?? 0
            ])
            ->toArray();

        return response()->json($dados);
    }

    // Revisões por período
    public function revisoesPorPeriodo(Request $request)
    {
        $inicio = $request->query('inicio', '2024-01-01');
        $fim = $request->query('fim', '2024-12-31');

        $dados = Revisao::whereBetween('data_revisao', [$inicio, $fim])->get()->toArray();
        return response()->json($dados);
    }

    // Marcas com mais revisões
    public function marcasComMaisRevisoes()
    {
        $dados = Revisao::join('veiculos', 'revisoes.veiculo_id', '=', 'veiculos.id')
            ->select('veiculos.marca', DB::raw('count(revisoes.id) as total_revisoes'))
            ->groupBy('veiculos.marca')
            ->orderByDesc('total_revisoes')
            ->get()
            ->map(fn($d) => [
                'marca' => $d->marca ?? 'Sem Marca',
                'total_revisoes' => (int) $d->total_revisoes
            ])
            ->toArray();

        return response()->json($dados);
    }

    // Pessoas com mais revisões
    public function pessoasComMaisRevisoes()
    {
        $dados = Revisao::join('veiculos', 'revisoes.veiculo_id', '=', 'veiculos.id')
            ->join('proprietarios', 'veiculos.proprietario_id', '=', 'proprietarios.id')
            ->select('proprietarios.nome', DB::raw('count(revisoes.id) as total_revisoes'))
            ->groupBy('proprietarios.nome')
            ->orderByDesc('total_revisoes')
            ->get()
            ->map(fn($d) => [
                'nome' => $d->nome,
                'total_revisoes' => (int) $d->total_revisoes
            ])
            ->toArray();

        return response()->json($dados);
    }

    // Média de tempo entre revisões
    public function mediaTempoEntreRevisoes()
    {
        $sql = "
            SELECT 
                p.nome, 
                AVG(diff) AS media_dias
            FROM (
                SELECT 
                    v.proprietario_id,
                    r.id,
                    r.data_revisao,
                    LEAD(r.data_revisao) OVER (PARTITION BY v.proprietario_id ORDER BY r.data_revisao) AS proxima_revisao,
                    (LEAD(r.data_revisao) OVER (PARTITION BY v.proprietario_id ORDER BY r.data_revisao) - r.data_revisao) AS diff
                FROM revisoes r
                JOIN veiculos v ON r.veiculo_id = v.id
            ) t
            JOIN proprietarios p ON t.proprietario_id = p.id
            WHERE t.diff IS NOT NULL
            GROUP BY p.nome;
        ";

        $dados = DB::select($sql);
        $dados = array_map(fn($d) => [
            'nome' => $d->nome,
            'media_dias' => (float) $d->media_dias
        ], $dados);

        return response()->json($dados);
    }

    // Próximas revisões
    public function proximasRevisoes()
    {
        $sql = "
            SELECT p.nome, v.modelo, r.data_revisao,
                (r.data_revisao + INTERVAL '180 day') AS proxima_revisao_aproximada
            FROM revisoes r
            JOIN veiculos v ON r.veiculo_id = v.id
            JOIN proprietarios p ON v.proprietario_id = p.id
            WHERE r.data_revisao = (
                SELECT MAX(data_revisao) FROM revisoes WHERE veiculo_id = v.id
            )
            ORDER BY proxima_revisao_aproximada ASC
        ";

        $dados = DB::select($sql);
        $dados = array_map(fn($d) => [
            'nome' => $d->nome,
            'modelo' => $d->modelo,
            'proxima_revisao_aproximada' => $d->proxima_revisao_aproximada
        ], $dados);

        return response()->json($dados);
    }
}
