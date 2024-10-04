<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class NotificacoesCalenadrios extends Model
{
    use HasFactory;
    
    protected $table = "events";
    
    protected $fillable = [
        "title",
        "color",
        "start",
        "end",
        "obs"      
    ];

    public function obra()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obra');
    }
}
