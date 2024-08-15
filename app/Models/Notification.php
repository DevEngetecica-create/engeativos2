<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    use HasFactory;
    
    protected $table = "notifications";
    
    protected $fillable = [
        "id_servico",
        "id_obra",
        "tipo",
        "mensagem",
        "usuario",
        "status",
        "link_acesso"
    ];

    public function obra()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obra');
    }
}
