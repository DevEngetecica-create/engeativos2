<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Situacao extends Model
{
    use HasFactory;
    protected $table = 'ativos_externos_status';

    protected $fillable = [
        'titulo',
        'classe',       
    ];
}
