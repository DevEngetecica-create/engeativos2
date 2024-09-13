<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfiguracaoUsuario extends Model
{
    use HasFactory;

    protected $table = "users";

      protected $fillable = [
            'name',
            'email',
            'password',
            'matricula',
            'avatar',
            'bloqueado',
        ];
        
    public function vinculo()
    {
        return $this->hasOne(CadastroUsuariosVinculo::class, 'id_usuario', 'id');
    }

    

}
