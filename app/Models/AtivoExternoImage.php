<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class AtivoExternoImage extends Model
{
    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];
    protected $table = 'ativos_externos_image';

    protected $fillable = [
        'id_ativos_externos_estoque',
        'id_usuario',
        'imagens',
        
    ];
    
    public function ativo_externo_estoque()
    {
        return $this->belongsTo(AtivoExternoEstoque::class, 'id_ativos_externos_estoque');
    }
    
}


