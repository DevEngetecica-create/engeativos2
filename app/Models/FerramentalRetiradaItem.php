<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FerramentalRetiradaItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $table = 'ativos_ferramental_retirada_item';
    protected $primaryKey = 'id_retirada';

    protected $fillable = [
        'id_ativo_externo',
        'id_retirada',
        'status'
    ];

    public function ativo_externo()
    {
        return $this->belongsTo(AtivoExternoEstoque::class, 'id_ativo_externo');
    }

}
