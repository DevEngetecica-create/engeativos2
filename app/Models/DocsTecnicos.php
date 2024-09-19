<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocsTecnicos extends Model
{
    use HasFactory;

    protected $table = "docs_tecnicos";

    protected $fillable = [
        'tipo_veiculo',
        'nome_documento',
        'validade',
        'user_create',
        'user_edit'
    ];

    public function veiculo()
    {
        return $this->belongsTo(Veiculo::class, 'id_veiculo');
    }

    public function nomeTipo_veiculo()
    {
        return $this->belongsTo(TiposVeiculos::class, 'tipo_veiculo');
    }

    public function docsLegais()
    {
        return $this->hasMany(DocsLegais::class, 'tipo_veiculo', 'id');
    }
}
