<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Proprietario;

class ProprietarioSeeder extends Seeder
{
    public function run()
    {
        $proprietarios = [
            ['nome' => 'Ana Paula', 'email' => 'ana@teste.com', 'sexo' => 'F', 'data_nascimento' => '1990-03-15'],
            ['nome' => 'Carlos Silva', 'email' => 'carlos@teste.com', 'sexo' => 'M', 'data_nascimento' => '1985-06-10'],
            ['nome' => 'Fernanda Souza', 'email' => 'fernanda@teste.com', 'sexo' => 'F', 'data_nascimento' => '1995-11-23'],
            ['nome' => 'João Pedro', 'email' => 'joao@teste.com', 'sexo' => 'M', 'data_nascimento' => '2000-02-05'],
        ];

        foreach ($proprietarios as $data) {
            Proprietario::create($data);
        }
    }
}