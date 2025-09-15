<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Proprietario;
use App\Models\Veiculo;
use App\Models\Revisao;
use Illuminate\Support\Facades\DB;

class RelatoriosController extends Controller
{
    // Relatório: Todos os veículos
    public function veiculos()
    {
        $dados = Veiculo::with('proprietario')->get();
        return response()->json($dados);
    }

    // Relatório: Veículos por pessoa, ordenado por nome
    public function veiculosPorPessoa()
    {
        $dados = Proprietario::with('veiculos')
            ->orderBy('nome')
            ->get()
            ->map(function ($p) {
                return [
                    'id' => $p->id,
                    'nome' => $p->nome,
                    'quantidade' => $p->veiculos->count(),
                    'veiculos' => $p->veiculos,
                ];
            });

        return response()->json($dados);
    }

    // Relatório: Quem tem mais veículos (homens ou mulheres)
    public function veiculosPorSexo()
    {
        $dados = Proprietario::select('sexo', DB::raw('count(veiculos.id) as total'))
            ->join('veiculos', 'proprietarios.id', '=', 'veiculos.proprietario_id')
            ->groupBy('sexo')
            ->orderByDesc('total')
            ->get();

        return response()->json($dados);
    }

    // Relatório: Todas as marcas ordenadas pelo número de veículos
    public function marcasPorQuantidade()
    {
        $dados = Veiculo::select('marca', DB::raw('count(*) as quantidade'))
            ->groupBy('marca')
            ->orderByDesc('quantidade')
            ->get();

        return response()->json($dados);
    }

    // Relatório: Totais de marcas ordenadas, separados entre homens e mulheres
    public function marcasPorSexo()
    {
        $dados = Veiculo::join('proprietarios', 'veiculos.proprietario_id', '=', 'proprietarios.id')
            ->select('veiculos.marca', 'proprietarios.sexo', DB::raw('count(*) as total'))
            ->groupBy('veiculos.marca', 'proprietarios.sexo')
            ->orderByDesc('total')
            ->get();

        return response()->json($dados);
    }

    // Relatório: Todas as pessoas
    public function pessoas()
    {
        $dados = Proprietario::all()->map(function ($p) {
            return [
                'id' => $p->id,
                'nome' => $p->nome,
                'quantidade' => $p->veiculos->count() ?? 0
            ];
        });

        return response()->json($dados);
    }

    // Relatório: Todas as revisões dentro de um período
    public function revisoesPorPeriodo(Request $request)
    {
        $inicio = $request->query('inicio', '2024-01-01');
        $fim = $request->query('fim', '2024-12-31');

        $dados = Revisao::whereBetween('data_revisao', [$inicio, $fim])->get();
        return response()->json($dados);
    }

    // Relatório: Marcas com maior número de revisões
    public function marcasComMaisRevisoes()
    {
        $dados = Revisao::join('veiculos', 'revisoes.veiculo_id', '=', 'veiculos.id')
            ->select('veiculos.marca', DB::raw('count(revisoes.id) as total_revisoes'))
            ->groupBy('veiculos.marca')
            ->orderByDesc('total_revisoes')
            ->get();

        return response()->json($dados);
    }

    // Relatório: Pessoas com maior número de revisões
    public function pessoasComMaisRevisoes()
    {
        $dados = Revisao::join('veiculos', 'revisoes.veiculo_id', '=', 'veiculos.id')
            ->join('proprietarios', 'veiculos.proprietario_id', '=', 'proprietarios.id')
            ->select('proprietarios.nome', DB::raw('count(revisoes.id) as total_revisoes'))
            ->groupBy('proprietarios.nome')
            ->orderByDesc('total_revisoes')
            ->get();

        return response()->json($dados);
    }

    // Relatório: Média de tempo entre uma revisão e outra de uma mesma pessoa
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
        return response()->json($dados);
    }

    // Relatório: Próximas revisões baseado no tempo médio da última revisão
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
        return response()->json($dados);
    }
}
