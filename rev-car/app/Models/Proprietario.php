<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Proprietario extends Model
{
    protected $table = 'proprietarios';

    protected $fillable = [
        'nome',
        'sexo',
        'data_nascimento',
        'email',
    ];

    public function veiculos()
    {
        return $this->hasMany(Veiculo::class, 'proprietario_id');
    }
}