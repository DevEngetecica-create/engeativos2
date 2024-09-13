<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstoqueEntrada extends Model
{
    use HasFactory;
    use SoftDeletes;


     protected $table = "estoque_entradas";

    protected $fillable = [
        'id_obra',
        'id_produto',
        'id_funcionario',
        'usuario',
        'usuario_edit',
        'quantidade_entrada',
        'valor_unitario_entrada',
        'valor_total_entrada',
        'cert_aut_entrada',
        'num_lote_entrada',
        'data_validade_lote_ca',
        'arquivo_ca',
        'nota_fical',
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