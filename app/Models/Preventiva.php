<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Preventiva extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $table = 'preventivas';

    protected $dates = ['deleted_at'];

    protected $fillable = [
        'veiculo_id',
        'user_id',
        'manutencao_id',
        'preventiva',
        'descricao',
        'status',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'veiculo_id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function manutencao()
    {
        return $this->belongsTo(VeiculoManutencao::class, 'manutencao_id');
    }
}
