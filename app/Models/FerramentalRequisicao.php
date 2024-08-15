<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FerramentalRequisicao extends Model
{
    use HasFactory;
    Use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = 'ativos_ferramental_requisicao';

    protected $fillable = [
        'id_solicitante',
        'id_despachante',
        'id_recebedor',
        'id_obra_origem',
        'id_obra_destino',
        'data_recebimento',
        'data_liberacao',
        'observacoes',
        'status'
    ];

    public function solicitante() {
        return $this->belongsTo(User::class, 'id_solicitante', 'id');
    }

    public function despachante() {
        return $this->belongsTo(User::class, 'id_despachante', 'id');
    }

    public function recebedor() {
        return $this->belongsTo(User::class, 'id_recebedor', 'id');
    }

    public function obraOrigem() {
        return $this->belongsTo(CadastroObra::class, 'id_obra_origem', 'id');
    }

    public function obraDestino() {
        return $this->belongsTo(CadastroObra::class, 'id_obra_destino', 'id');
    }

    public function situacao() {
        return $this->belongsTo(FerramentalRequisicaoStatus::class, 'status', 'id');
    }


}
