<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CadastroFuncionarioSetor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "funcionarios_setor";
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'nome_setor',
        'user_create',
        'user_edit'
    ];
}
