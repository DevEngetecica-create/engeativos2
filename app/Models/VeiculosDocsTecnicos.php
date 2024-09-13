<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VeiculosDocsTecnicos extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_tipo_veiculo',
        'id_doc_tecnico',
        'id_veiculo',
        'arquivo',
        'data_documento',
        'validade',
        'data_validade',
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'id_veiculo');
    }
    public function tipo_veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'id_tipo_veiculo');
    }
   
    public function tipo_doc_legal()
    {
        return $this->belongsTo(DocsTecnicos::class, 'id_doc_tecnico');
    }
}
