<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class VeiculoQuilometragem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'veiculo_quilometragems';
    protected $dates = ['deleted_at'];
   
    protected $fillable = [
        'veiculo_id',
        'id_abastecimento',
        'id_funcionario',
        'usuario',
        'data_quilometragem',
        'quilometragem_atual',
        'quilometragem_nova',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario');
    }

}
