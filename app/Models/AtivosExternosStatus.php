<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AtivosExternosStatus extends Model
{
    use HasFactory;

    protected $table = 'ativos_externos_status';

    public function status()
    {
        return $this->belongsTo(AtivosExternosStatus::class, 'id', 'status');
    }

}
