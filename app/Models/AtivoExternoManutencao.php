<?php



namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class AtivoExternoManutencao extends Model

{

    use HasFactory;

    use SoftDeletes;

    protected $dates = ['deleted_at'];

    protected $table = "ativos_externos_manutencao";

    protected $fillable = [

        'id_ativo_externo_estoque',
        'id_ativo_externo',
        'id_obra',
        'id_fornecedor',
        'id_user_solicitante',
        'id_status',
        'nomeAtivo',
        'description',
        'valor',
        'data_retirada',
        'data_prevista',
        'data_realizada'
    ];



    public function ativo_externo_estoque()
    {

        return $this->belongsTo(AtivoExternoEstoque::class, 'id_ativo_externo_estoque');
    }

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

        return $this->belongsTo(AtivoExernoStatus::class, 'id_status');
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

        return $this->belongsTo(FerramentalRetiradaItem::class, 'id_ativo_externo_estoque');
    }

    public function fornecedor()
    {

        return $this->belongsTo(CadastroFornecedor::class, 'id_fornecedor');
    }

    public function funcionario()
    {

        return $this->belongsTo(CadastroFuncionario::class, 'id_funcionario', 'id');
    }
}
