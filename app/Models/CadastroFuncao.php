<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CadastroFuncao extends Model
{
    use HasFactory;

    protected $table = "funcionarios_funcoes";
    protected $dates = ['deleted_at'];

    public function funcionarios()
    {
        return $this->hasMany(CadastroFuncionario::class);
    }
}
