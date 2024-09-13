<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracaoUsuarioNiveis extends Model
{
    use HasFactory;

	protected $table = 'usuarios_niveis';
	
	public $timestamps = true;

	// Por padrão o laravel requer para as o campo de chave primaria o nome
	// ID, porem quando se quer utilizar um nome diferente se faz necessário 
	// aplicar a regra a baixo.
    protected $primaryKey = 'id';

    // Com esta proteção permite que somente os campos identificados no array
    // serão manipulados.
    protected $fillable =   [ 'id'                   
							, 'titulo'             
							, 'created_at'        
							, 'updated_at'                						                						           						 
					        ];    
}
