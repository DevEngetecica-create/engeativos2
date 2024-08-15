<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VeiculoIpva extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'veiculo_id',
        'referencia_ano',
        'valor',
        'data_de_vencimento',
        'data_de_pagamento',
        'nome_anexo_ipva',
        'extensao'
    ];
    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }
    
    public function quilometragens()
    {   
        return $this->hasMany(VeiculoQuilometragem::class, 'veiculo_id');
    }
}


