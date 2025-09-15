<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Revisao;

class RevisaoSeeder extends Seeder
{
    public function run()
    {
        $revisoes = [
            ['veiculo_id' => 1, 'data_revisao' => '2024-01-10', 'descricao' => 'Troca de óleo'],
            ['veiculo_id' => 2, 'data_revisao' => '2024-02-20', 'descricao' => 'Revisão dos freios'],
            ['veiculo_id' => 3, 'data_revisao' => '2024-03-15', 'descricao' => 'Alinhamento'],
            ['veiculo_id' => 4, 'data_revisao' => '2024-04-05', 'descricao' => 'Troca de filtros'],
            ['veiculo_id' => 5, 'data_revisao' => '2024-05-12', 'descricao' => 'Verificação elétrica'],
            ['veiculo_id' => 1, 'data_revisao' => '2024-06-18', 'descricao' => 'Limpeza do ar-condicionado'],
        ];

        foreach ($revisoes as $data) {
            Revisao::create($data);
        }
    }
}