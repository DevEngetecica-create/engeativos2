<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FerramentalRequisicaoTransito extends Model
{
    use HasFactory;

    protected $table = 'ferramental_requisicao_transitos';

    protected $fillable = [
        'id_requisicao',
        'id_ativo',
        'id_obra_origem',
        'id_obra_destino',
        'observacao_recebimento',
        'status'
    ];

    public function requisicao()
    {
        return $this->belongsTo(FerramentalRequisicao::class, 'id_requisicao');
    }

    public function ativo()
    {
        return $this->belongsTo(AtivoExternoEstoque::class, 'id_ativo');
    }

    public function obraOrigem()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obra_origem');
    }

    public function obraDestino()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obra_destino');
    }

    public function status()
    {
        return $this->belongsTo(FerramentalRequisicaoStatus::class, 'status');
    }

}
