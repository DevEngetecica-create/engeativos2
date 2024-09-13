<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FerramentalRequisicaoItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'ativos_ferramental_requisicao_item';

    protected $fillable = [
        'id_ativo_externo',
        'id_requisicao',
        'quantidade_solicitada',
        'quantidade_liberada',
        'status',
        'status_recebido',
        'observacao_recebido'
    ];

    public function ativo_externo()
    {
        return $this->belongsTo(AtivoExterno::class,'id_ativo_externo');
    }

    public function ativo_externo_estoque()
    {
        return $this->belongsTo(AtivoExternoEstoque::class,'id_ativo_externo');
    }

    public function requisicao()
    {
        return $this->belongsTo(FerramentalRequisicao::class, 'id_requisicao');
    }

    public function situacao()
    {
        return $this->belongsTo(FerramentalRequisicaoStatus::class, 'status');
    }

    public function situacao_recebido()
    {
        return $this->belongsTo(FerramentalRequisicaoStatus::class, 'status_recebido', 'id');
    }
}
