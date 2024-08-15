<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\SoftDeletes;

class FerramentalRetirada extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "ativos_ferramental_retirada";
    protected $fillable = [
        "id_relacionamento",
        "id_obra",
        "id_usuario",
        "id_funcionario",
        "termo_responsabilidade_gerado",
        "data_devolucao_prevista",
        "data_devolucao",
        "devolucao_observacoes",
        "status",
        "observacoes",
    ];

    public function obra()
    {
        return $this->belongsTo(CadastroObra::class, 'id_obra', 'id');
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id');
    }

    public function ativo_externo()

    {

        return $this->belongsTo(AtivoExterno::class, 'id_ativo_externo');

    }

    public function funcionario()
    {
        return $this->belongsTo(CadastroFuncionario::class, 'id_funcionario', 'id');
    }

    public function situacao()
    {
        return $this->belongsTo(FerramentalRetiradaStatus::class, 'status', 'id');
    }

    public function configuracao()

    {

        return $this->belongsTo(AtivoExterno::class, 'id_ativo_externo');
    }

    public function status()
    {
        return $this->belongsTo(AtivosExternosStatus::class, 'status', 'id');
    }


    static function getRetirada()
    {
        return DB::table('ativos_ferramental_retirada')
            ->select(
                'ativos_ferramental_retirada.*',
                'users.name as solicitante',
                'funcionarios.nome as funcionario',
                'funcionarios.matricula as funcionario_matricula',
                'obras.codigo_obra',
                'obras.razao_social'
            )
            ->join("users", "users.id", "=", "ativos_ferramental_retirada.id_usuario")
            ->join("funcionarios", "funcionarios.id", "=", "ativos_ferramental_retirada.id_funcionario")
            ->join("obras", "obras.id", "=", "ativos_ferramental_retirada.id_obra")
            ->get();
    }

    /**
     * Retirada de Itens de Acordo com o int(id_retirada)
     */
    static function getRetiradaItems(int $id)
    {
        $retirada = DB::table('ativos_ferramental_retirada')
            ->select(
                'ativos_ferramental_retirada.*',
                'users.name',
                'funcionarios.nome as funcionario',
                'funcionarios.id as idFuncionario',
                'funcionarios.cpf',
                'funcionarios.matricula as funcionario_matricula',
                'obras.codigo_obra',
                'obras.razao_social',
                'ativos_externos_estoque.patrimonio'
            )
            ->selectRaw('CAST(REPLACE(ativos_externos_estoque.valor, ".", "") AS DECIMAL(10, 2)) ')
            ->join("ativos_ferramental_retirada_item", "ativos_ferramental_retirada_item.id_retirada", "=", "ativos_ferramental_retirada.id")
            ->join("ativos_externos_estoque", "ativos_externos_estoque.id", "=", "ativos_ferramental_retirada_item.id_ativo_externo")
            ->join("users", "users.id", "=", "ativos_ferramental_retirada.id_usuario")
            ->join("funcionarios", "funcionarios.id", "=", "ativos_ferramental_retirada.id_funcionario")
            ->join("obras", "obras.id", "=", "ativos_ferramental_retirada.id_obra")
            ->where('ativos_ferramental_retirada.id', $id)
            ->first();


        if ($retirada) {

            /** Faz a busca dos itens de acordo com a retirada */
            $retirada->itens = DB::table('ativos_ferramental_retirada_item')

                ->select(
                    'ativos_ferramental_retirada_item.*',
                    'ativos_externos_estoque.patrimonio as item_codigo_patrimonio',
                    'ativos_externos.titulo as item_nome',
                    'ativos_externos_status.titulo as status_item',
                    'ativos_externos_status.classe as status_classe',
                    'ativos_externos_estoque.patrimonio',
                    'ativos_externos_estoque.valor'
                )

                ->join("ativos_externos_estoque", "ativos_externos_estoque.id", "=", "ativos_ferramental_retirada_item.id_ativo_externo")
                ->join("ativos_externos", "ativos_externos.id", "=", "ativos_externos_estoque.id_ativo_externo")
                ->join("ativos_externos_status", "ativos_externos_status.id", "=", "ativos_externos_estoque.status")
                ->where('ativos_ferramental_retirada_item.id_retirada', $id)
                ->get();

            /** Verifica se tem anexo */
            $retirada->anexo = DB::table('anexos')
                ->where('id_item', $id)
                ->where('id_modulo', '18') // Retirada
                ->get()
                ->first();
        }


        return $retirada;
    }
}
