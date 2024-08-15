<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CadastroFolgaFuncionarios extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "funcionarios_folga";
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id_funcionario',
        'id_obra',
        'data_inicio',
        'data_fim'
    
    ];
    
    public function obra()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obra');
    }

    public function funcionarios()
    {
        return $this->belongsTo(CadastroFuncionario::class, 'id_funcionario');
    }

}
