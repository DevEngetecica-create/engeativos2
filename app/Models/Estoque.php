<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estoque extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "estoque";
    protected $fillable = [
        'id_obra',
        'id_categoria',
        'id_subcategoria',
        'id_marca',
        'usuario',
        'nome_produto',
        'valor_unitario',
        'unidade',
        'status_produto',
        'image',
    ];

    public function categorias()
    {
        return $this->belongsTo(EstoqueCategoria::class, 'id_categoria');
    }

    public function subcategorias()
    {
        return $this->belongsTo(EstoqueSubCategoria::class, 'id_subcategoria');
    }

    public function obra()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obra');
    }

    public function fornecedor()
    {
        return $this->belongsTo(CadastroFornecedor::class, 'id_fornecedor');
    }

    public function marca()
    {
        return $this->belongsTo(EstoqueMarcas::class, 'id_marca');
    }

    public function entradas()
    {
        return $this->hasMany(EstoqueEntrada::class, 'id_produto');
    }

    public function saidas()
    {
        return $this->hasMany(EstoqueSaida::class, 'id_produto');
    }

    // Método para calcular o saldo atual do estoque
    public function saldoEstoque()
    {
        $totalEntradas = $this->entradas()->sum('quantidade_entrada');
        $totalSaidas = $this->saidas()->sum('quantidade_saida');
        return $totalEntradas - $totalSaidas;
    }

    public function getLotesComSaldoAttribute()
    {
        return $this->entradas->map(function ($entrada) {
            $saidaTotal = $this->saidas->where('num_lote_saida', $entrada->num_lote_entrada)->sum('quantidade_saida');
            return [
                'numeroLote' => $entrada->num_lote_entrada,
                'validade' => $entrada->data_validade_lote_ca,
                'quantidadeDisponivel' => $entrada->quantidade_entrada - $saidaTotal
            ];
        });
    }
}
