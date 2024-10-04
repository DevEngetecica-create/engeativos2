<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FerramentalRequisicaoStatus extends Model
{
    use HasFactory;

    protected $table = 'ativos_ferramental_requisicao_status';

    public function requisicoes()
    {
        return $this->hasMany(FerramentalRequisicao::class, 'status');
    }

}

