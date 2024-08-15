<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FuncaoEpi extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'funcionario_funcao_epi';
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'id_funcao',
        'id_estoque',
        'cert_aut'
    ];

    public function funcao()
    {
        return $this->belongsTo(FuncaoFuncionario::class, 'id_funcao');
    }

    public function produto()
    {
        return $this->belongsTo(Estoque::class, 'id_estoque',  'id');
    }

}
