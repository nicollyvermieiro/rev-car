<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Veiculo extends Model
{
    protected $table = 'veiculos';

    protected $fillable = [
        'marca',
        'modelo',
        'placa',
        'proprietario_id',
    ];

    public function proprietario()
    {
        return $this->belongsTo(Proprietario::class, 'proprietario_id');
    }

    public function revisoes()
    {
        return $this->hasMany(Revisao::class, 'veiculo_id');
    }
}