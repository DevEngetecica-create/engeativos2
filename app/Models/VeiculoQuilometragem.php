<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VeiculoQuilometragem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'veiculo_quilometragems';
    protected $dates = ['deleted_at'];
    
    protected $fillable = [
        'veiculo_id',
        'id_abastecimento',
        'id_funcionario',
        'id_obra',
        'user_create',
        'user_edit',
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
    
    /* public function abastecimento()
    {
        return $this->belongsTo(VeiculoAbastecimento::class, 'id_abastecimento');
    } */
}
