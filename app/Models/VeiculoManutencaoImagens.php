<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VeiculoManutencaoImagens extends Model
{
    use HasFactory;

    protected $table = "veiculo_manutencoes_imagens";

    protected $fillable = ['manutencao_id', 'nome_imagem', 'descricao'];
    
}
