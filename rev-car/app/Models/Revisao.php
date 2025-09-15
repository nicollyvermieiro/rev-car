<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Revisao extends Model
{
    protected $table = 'revisoes';

    protected $fillable = [
        'veiculo_id',
        'data_revisao',
        'descricao',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }
}