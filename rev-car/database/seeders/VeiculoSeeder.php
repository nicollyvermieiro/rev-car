<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Veiculo;

class VeiculoSeeder extends Seeder
{
    public function run()
    {
        $veiculos = [
            ['marca' => 'Fiat', 'modelo' => 'Uno', 'placa' => 'ABC-1234', 'proprietario_id' => 1],
            ['marca' => 'Volkswagen', 'modelo' => 'Gol', 'placa' => 'DEF-5678', 'proprietario_id' => 2],
            ['marca' => 'Chevrolet', 'modelo' => 'Onix', 'placa' => 'GHI-9012', 'proprietario_id' => 1],
            ['marca' => 'Honda', 'modelo' => 'Civic', 'placa' => 'JKL-3456', 'proprietario_id' => 3],
            ['marca' => 'Toyota', 'modelo' => 'Corolla', 'placa' => 'MNO-7890', 'proprietario_id' => 4],
        ];

        foreach ($veiculos as $data) {
            Veiculo::create($data);
        }
    }
}