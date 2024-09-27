<?php



namespace App\Models;



use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

use Illuminate\Support\Facades\DB;



class AtivoExternoEstoque extends Model

{

    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = "ativos_externos_estoque";


    protected $fillable = [

        'id_ativo_externo',
        'id_obra',
        'patrimonio',
        'titulo',
        'data_descarte',
        'valor',
        'calibracao',
        'status',   
    ];



  

    public function ativo_externo()

    {

        return $this->belongsTo(AtivoExterno::class, 'id_ativo_externo');

    }



    public function obra()

    {

        return $this->belongsTo(CadastroObra::class, 'id_obra');

    }



    public function situacao()

    {

        return $this->belongsTo(AtivoExernoStatus::class, 'status');

    }



    public function configuracao()

    {

        return $this->belongsTo(AtivoExterno::class, 'id_ativo_externo');

    }
    
    public function retirada()
    {
        return $this->belongsTo(FerramentalRetirada::class, 'id_obra');
    }
    
   public function emOprecacao()
    {
        
        return $this->belongsTo(FerramentalRetiradaItem::class, 'id_ativo_externo');
    }

    public function funcionario()
    {
        
        return $this->belongsTo(CadastroFuncionario::class, 'id_funcionario', 'id');
        
    }




}

