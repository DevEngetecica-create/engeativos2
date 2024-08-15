<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Anexo extends Model
{
    use HasFactory;

    protected $table = "anexos";
    
    protected $fillable =[
            'id_modulo',
            'id_item',
            'titulo',
            'data_vencimento',
            'data_calibracao',
            'data_cadastro',
            'nome_empresa',
            'tipo',
            'arquivo',
            'descricao',
            'nome_modulo',
        ];


}
