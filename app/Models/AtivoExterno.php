<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AtivoExterno extends Model
{
    use HasFactory;


    protected $table = "ativos_externos";

    protected $fillabel = [

    ];

    public function categoria()
    {
        return $this->belongsTo(AtivoConfiguracao::class, 'id_ativo_configuracao', 'id');
    }

    public function estoque()
    {
        return $this->hasMany(AtivoExternoEstoque::class, 'id_ativo_externo', 'id')
        ->where('status', 4);
    }

    public function estoque_requisicao()
    {
        return $this->hasMany(AtivoExternoEstoque::class, 'id_ativo_externo', 'id')
        ->whereIn('status', [4, 11]);
    }

    public function configuracao()
    {
        return $this->belongsTo(AtivoConfiguracao::class, 'id_ativo_configuracao', 'id');
    }

}
