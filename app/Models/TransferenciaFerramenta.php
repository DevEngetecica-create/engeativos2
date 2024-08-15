<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class TransferenciaFerramenta extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "ativos_trensfer_ferramentas";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id_obraOrigem',
        'id_obraDestino',
        'descricao',
        'id_transferencia',
        'id_ativo_externo_estoque',
        'id_usuario',
       
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario');
    }

    public function ativo_externo_estoque()
    {
        return $this->belongsTo(AtivoExternoEstoque::class, 'id_ativo_externo_estoque');
    }

    public function obraOrigem()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obraOrigem');

    }
    
    public function obraDestino()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obradestino');

    }
}
