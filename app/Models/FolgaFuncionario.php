<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FolgaFuncionario extends Model
{
    use HasFactory;

    protected $table = 'folga_funcionario';

    protected $fillable = [
        'data_inicio',
        'data_fim',
        'id_funcionario',
        'id_obra',
        'updated_at',
        'delete_at'
    ];

    public function funcionarios()
    {
        return $this->hasMany(CadastroFuncionario::class, 'id');
    }
}
