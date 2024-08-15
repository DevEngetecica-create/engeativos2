<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CadastroFornecedor extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "fornecedores";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'razao_social',
        'cnpj',
        'cep',
        'endereco',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'email',
        'celular',
        'status',
    ];

    public function abastecimento()
    {
        return $this->hasOne(VeiculoAbastecimento::class);
    }

    public function contatos()
    {
        return $this->hasMany(ContatoFornecedor::class, 'id_fornecedor');
    }
}
