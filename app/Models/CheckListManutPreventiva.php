<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckListManutPreventiva extends Model
{
    use HasFactory;

    protected $table = 'check_list_manut_preventiva';

    protected $fillable = [
        'id_manut_preventiva',
        'id_veiculo',
        'nome_servico',
        'situacaoPreventiva',
        'periodo',
        'situacao',
        'observacoes',
        'files'
    ];

    protected $casts = [
        'periodo' => 'array',
        'situacao' => 'array',
        'observacoes'=> 'array',
        'files'=> 'array',
        'situacaoPreventiva'=> 'array'
    ];

    public function setAttribute($key, $value)
    {
        if (in_array($key, ['nome_servico', 'periodo', 'situacao', 'observacoes', 'files', 'situacaoPreventiva']) && is_array($value)) {
            $this->attributes[$key] = json_encode($value);
        } else {
            parent::setAttribute($key, $value);
        }
    }
}
