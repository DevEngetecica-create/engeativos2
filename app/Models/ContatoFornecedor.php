<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContatoFornecedor extends Model
{
    use HasFactory;

    protected $table = 'contato_fornecedors';

    protected $fillable = [
        'id_fornecedor',
        'setor',
        'nome',
        'email',
        'telefone'
    ];

    public function fornecedor()
    {
        return $this->belongsTo(CadastroFornecedor::class,  'id_fornecedor', 'id');
    }
}
