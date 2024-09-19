<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VeiculoPreventiva extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome_preventiva',
        'nome_servico',
        'tipo_veiculo',
        'situacao',
        'periodo',
        'tipo'
    ];

    protected $casts = [
        'periodo' => 'array'
    ];

    public function setAttribute($key, $value)
    {
        if (in_array($key, ['nome_servico', 'situacao', 'periodo']) && is_array($value)) {
            $this->attributes[$key] = json_encode($value);
        } else {
            parent::setAttribute($key, $value);
        }
    }

    public function tipo_veiculos()
    {
        return $this->belongsTo(TiposVeiculos::class, 'tipo_veiculo');
    }
}
