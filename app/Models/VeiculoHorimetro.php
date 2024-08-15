<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VeiculoHorimetro extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'veiculo_horimetro';
    protected $dates = ['deleted_at'];
   
    protected $fillable = [
        'veiculo_id',
        'id_abastecimento',
        'id_funcionario',
        'usuario',
        'data_horimetro',
        'horimetro_atual',
        'horimetro_novo',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }
    
    public function funcionario()
    {
        return $this->belongsTo(CadastroFuncionario::class, 'id_funcionario');
    }
    
    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario');
    }

}
