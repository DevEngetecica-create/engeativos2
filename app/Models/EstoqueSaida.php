<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstoqueSaida extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $table = "estoque_saidas";

    protected $fillabel = [
        'id_obra',
        'id_requisicao',
        'id_ordem_producao',
        'id_produto',
        'id_funcionario',
        'id_categoria',
        'usuario',
        'quantidade_saida',
        'valor_unitario_saida',
        'valor_total_saida',
        'cert_aut_saida',
        'num_lote_saida',
        'assi_funcionario_saida',
        'assi_almoxarife_saida',

    ];

    public function categoria()
    {
        return $this->belongsTo(AtivoConfiguracao::class, 'id_categoria');
    }
    public function obra()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obra');
    }

    public function produto()
    {
        return $this->belongsTo(Estoque::class, 'id_produto');
    }

    public function funcionario()
    {
        return $this->belongsTo(CadastroFuncionario::class, 'id_funcionario');
    }

    
}
