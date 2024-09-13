<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TransferenciaFerramentaObra extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "ativos_transferencias";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id_obraOrigem',
        'id_obraDestino',       
        'id_usuario',
        'motivo_transferencia',
        'status'
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function id_ativo_externo_estoque()
    {
        return $this->hasMany(AtivoExternoEstoque::class, 'id_ativo_externo_estoque');
    }

    public function obraOrigem()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obraOrigem');

    }
    
    public function obraDestino()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obraDestino');

    }
    
    public function situacao()
    {
        return $this->belongsTo(AtivosExternosStatus::class, 'status');

    }
}
