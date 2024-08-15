<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CadastroUsuariosVinculo extends Model
{
    use HasFactory;
    protected $table = "usuarios_vinculos";
    protected $primaryKey = "id_usuario";

    protected $fillable = [
        'id_usuario',
        'id_obra',
        'id_funcionario',
        'id_nivel',
        'status'
    ];

    public function vinculo()
    {
        return $this->hasMany(CadastroFuncionario::class, 'id_funcao');
    }

    public function vinculo_funcionario()
    {
        return $this->hasOne(CadastroFuncionario::class, 'id', 'id_funcionario');
    }

    public function vinculo_obra()
    {
        return $this->hasOne(CadastroObra::class, 'id', 'id_obra');
    }
}
