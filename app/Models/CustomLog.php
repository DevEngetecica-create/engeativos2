<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class CustomLog extends Authenticatable

{
    use HasFactory;

    protected $table = 'logs';

    protected $fillable = [
        'id_usuario',
        'id_modulo',
        'id_item',
        'tipo',
        'metodo',
        'drescricao',
        'detalhes',
        'ip_acesso',
    ];

}
