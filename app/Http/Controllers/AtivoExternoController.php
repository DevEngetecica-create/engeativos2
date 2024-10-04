<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{
    AtivoConfiguracao,
    AtivoExternoEstoque,
    AtivoExterno,
    AtivoExternoEstoqueItem,
    AtivosExternosStatus,
    CadastroEmpresa,
    CadastroObra,
    Anexo,
    FerramentalRetirada,
    FerramentalRetiradaItem
};

use App\Models\Graficos\GraficosAtivosExternos;

use App\Traits\{
    Configuracao,
    FuncoesAdaptadas
};
use Illuminate\Contracts\Cache\Store;
use Illuminate\Support\Facades\Response;
use App\Report\AtivosExternosReport;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\{
    Auth,
    Storage,
    Log
};
use Illuminate\Support\Facades\Session;

use chillerlan\QRCode\{QRCode, QROptions};
use chillerlan\QRCode\Data\QRMatrix;
use chillerlan\QRCode\Output\QRFpdf;

class AtivoExternoController extends Controller
{

    use Configuracao, FuncoesAdaptadas;

    protected $ativoExternoEstoque;
    protected $ativoExternoEstoque_report;

    public function __construct(

        AtivoExternoEstoque $ativoExternoEstoque,
        AtivosExternosReport $ativoExternoEstoque_report

    ) {
        $this->ativoExternoEstoque = $ativoExternoEstoque;
        $this->ativoExternoEstoque_report = $ativoExternoEstoque_report;
    }

    public function index(Request $request, AtivoExterno $ativo)
    {


        if (Session::get('obra')['id'] == null) {
            $ativos = AtivoExternoEstoque::with('configuracao', 'situacao', 'obra')
                ->paginate(10);
        } else {
            $ativos = AtivoExternoEstoque::where('id_obra', Session::get('obra')['id'])
                ->with('configuracao', 'obra', 'situacao')
                ->paginate(10);
        }

        if (Session::get('obra')['id'] == null) {
            $obras = CadastroObra::orderByDesc('id')->get();
        } else {
            $obras = CadastroObra::where('id', Session::get('obra')['id'])->orderByDesc('id')->get();
        }

        $estoques = AtivoExternoEstoque::with('obra', 'situacao', 'ativo_externo')->where('id', 1)->get();
        $categorias = AtivoConfiguracao::where('id_relacionamento', '>', 0)->get();
        $situacoes = AtivosExternosStatus::all();

        $totalAtivos = GraficosAtivosExternos::totalAtivos();


        $ativo_configuracoes = AtivoConfiguracao::where('id_relacionamento', '>', 0)->where('status', 'Ativo')->get();

        $empresas = CadastroEmpresa::all();

        $countStatus = GraficosAtivosExternos::countStatus();
        $valorTotalAtivos = GraficosAtivosExternos::valorTotalAtivos();

        return view('pages.ativos.externos.index', compact('obras', 'estoques', 'categorias', 'situacoes', 'ativo_configuracoes', 'ativos', 'countStatus', 'valorTotalAtivos', 'totalAtivos'));
    }


    public function search(Request $request, AtivoExterno $ativo)
    {

        $id_obra = Session::get('obra')['id'];


        // dd($id_obra);

        $listPage = request('lista') ?? 10;

        $search = request('search');

        $status = $request->status_ferramentas;


        if ($id_obra == null) {
            $obras = CadastroObra::orderByDesc('id')->get();
        } else {
            $obras = CadastroObra::where('id', $id_obra)->orderByDesc('id')->get();
        }

        if ($id_obra == null) {

            //PESQUISAR EM TODAS AS OBRAS

            if ($request->status_ferramentas != null) {

                $ativos = AtivoExternoEstoque::with('configuracao', 'situacao', 'emOprecacao', 'funcionario')
                    ->where(function ($query) use ($status) {
                        $query->where('status', 'like', "%$status%")
                            //->where('id_obra', $id_obra)
                        ;
                    })
                    ->when($search, function ($query) use ($search) {
                        $query->whereHas('configuracao', function ($query) use ($search) {
                            $query->where('titulo', 'like', "%$search%");
                        });
                    })
                    ->paginate($listPage);


                $countStatus = AtivoExternoEstoque::select(
                    'ativos_externos_status.id AS idStatus',
                    'ativos_externos_status.titulo',
                    'ativos_externos_status.classe',
                    'ativos_externos.status AS statusAtivo'
                )
                    ->selectRaw('COUNT(ativos_externos_estoque.status) AS totalStatus')
                    ->join('ativos_externos_status', 'ativos_externos_estoque.status', '=', 'ativos_externos_status.id')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->Where('ativos_externos.titulo', 'like', "%$search%")
                    ->where('ativos_externos_estoque.status',  $status)
                    ->groupBy('ativos_externos_status.id', 'ativos_externos_status.titulo', 'ativos_externos_status.classe', 'ativos_externos.status')
                    ->get();



                $totalAtivos = AtivoExternoEstoque::selectRaw('COUNT(ativos_externos_estoque.id) AS totalAtivos')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    //->where('id_obra', $id_obra)
                    ->where('ativos_externos_estoque.status',  $status)
                    ->Where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();

                $valorTotalAtivos = AtivoExternoEstoque::select(DB::raw('SUM(CAST(valor AS DECIMAL(13, 2))) as somaValorTotalFerramentasObra'))
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    //->where('id_obra', $id_obra)
                    ->where('ativos_externos_estoque.status',  $status)
                    ->Where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();
            } else {


                $ativos = AtivoExternoEstoque::where(function ($query) use ($search) {
                    $query->where('patrimonio', 'like', "%$search%")
                        ->orWhere('calibracao', 'like', "%$search%")
                        //->where('id_obra', $id_obra)
                    ;
                })
                    ->orWhereHas('configuracao', function ($query) use ($search) {
                        $query->where('titulo', 'like', "%$search%");
                    })
                    ->with('configuracao', 'situacao')
                    ->paginate($listPage);

                // ******* CONTAGEM ***** POR STATUS POR OBRAS

                $countStatus = AtivoExternoEstoque::select(
                    'ativos_externos_status.id AS idStatus',
                    'ativos_externos_status.titulo',
                    'ativos_externos_status.classe',
                    'ativos_externos.status AS statusAtivo'
                )
                    ->selectRaw('COUNT(ativos_externos_estoque.status) AS totalStatus')
                    ->join('ativos_externos_status', 'ativos_externos_estoque.status', '=', 'ativos_externos_status.id')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->groupBy('ativos_externos_status.id', 'ativos_externos_status.titulo', 'ativos_externos_status.classe', 'ativos_externos.status')
                    ->Where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();


                $totalAtivos = AtivoExternoEstoque::selectRaw('COUNT(ativos_externos_estoque.id) AS totalAtivos')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->Where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();

                $valorTotalAtivos = AtivoExternoEstoque::select(DB::raw('SUM(CAST(valor AS DECIMAL(13, 2))) as somaValorTotalFerramentasObra'))
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->Where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();
            }
        } else {


            // *******  PESQUISAR POR OBRAS  *******

            if ($request->status_ferramentas != null) {
                $ativos = AtivoExternoEstoque::where('id_obra', $id_obra)
                    ->with('configuracao', 'obra', 'situacao', 'emOprecacao', 'funcionario', 'retirada')
                    ->where(function ($query) use ($status, $id_obra) {
                        $query->where('status', 'like', "%$status%")
                            ->where('id_obra', $id_obra);
                    })
                    ->when($search, function ($query) use ($search) {
                        $query->whereHas('configuracao', function ($query) use ($search) {
                            $query->where('titulo', 'like', "%$search%");
                        });
                    })
                    ->paginate($listPage);


                $countStatus = AtivoExternoEstoque::select(
                    'ativos_externos_status.id AS idStatus',
                    'ativos_externos_status.titulo',
                    'ativos_externos_status.classe',
                    'ativos_externos.status AS statusAtivo'
                )
                    ->selectRaw('COUNT(ativos_externos_estoque.status) AS totalStatus')
                    ->join('ativos_externos_status', 'ativos_externos_estoque.status', '=', 'ativos_externos_status.id')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('id_obra', $id_obra)
                    ->Where('ativos_externos.titulo', 'like', "%$search%")
                    ->where('ativos_externos_estoque.status',  $status)
                    ->groupBy('ativos_externos_status.id', 'ativos_externos_status.titulo', 'ativos_externos_status.classe', 'ativos_externos.status')
                    ->get();


                $totalAtivos = AtivoExternoEstoque::selectRaw('COUNT(ativos_externos_estoque.id) AS totalAtivos')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('id_obra', $id_obra)
                    ->where('ativos_externos_estoque.status',  $status)
                    ->Where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();

                $valorTotalAtivos = AtivoExternoEstoque::select(DB::raw('SUM(CAST(valor AS DECIMAL(13, 2))) as somaValorTotalFerramentasObra'))
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('id_obra', $id_obra)
                    ->where('ativos_externos_estoque.status',  $status)
                    ->Where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();
            } else {


                $ativos = AtivoExternoEstoque::where('id_obra', $id_obra)
                    ->where(function ($query) use ($search, $id_obra) {
                        $query->where('patrimonio', 'like', "%$search%")
                            ->orWhere('calibracao', 'like', "%$search%")
                            ->where('id_obra', $id_obra);
                    })
                    ->orWhereHas('configuracao', function ($query) use ($search, $id_obra) {
                        $query->where('titulo', 'like', "%$search%")
                            ->where('id_obra', "{$id_obra}");
                    })
                    ->with('configuracao', 'obra', 'situacao', 'emOprecacao', 'funcionario', 'retirada')
                    ->paginate($listPage);



                // ******* CONTAGEM ***** POR STATUS POR OBRAS

                $countStatus = AtivoExternoEstoque::select(
                    'ativos_externos_status.id AS idStatus',
                    'ativos_externos_status.titulo',
                    'ativos_externos_status.classe',
                    'ativos_externos.status AS statusAtivo'
                )
                    ->selectRaw('COUNT(ativos_externos_estoque.status) AS totalStatus')
                    ->join('ativos_externos_status', 'ativos_externos_estoque.status', '=', 'ativos_externos_status.id')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('id_obra', $id_obra)
                    ->Where('ativos_externos.titulo', 'like', "%$search%")
                    ->groupBy('ativos_externos_status.id', 'ativos_externos_status.titulo', 'ativos_externos_status.classe', 'ativos_externos.status')
                    ->get();


                $totalAtivos = AtivoExternoEstoque::selectRaw('COUNT(ativos_externos_estoque.id) AS totalAtivos')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('id_obra', $id_obra)
                    ->Where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();

                $valorTotalAtivos = AtivoExternoEstoque::select(DB::raw('SUM(CAST(valor AS DECIMAL(13, 2))) as somaValorTotalFerramentasObra'))
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('id_obra', $id_obra)
                    ->Where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();
            }
        }

        $itensRetirados = DB::table('ativos_ferramental_retirada')
            ->select(
                'ativos_ferramental_retirada.*',
                'ativos_ferramental_retirada.status as statusRetirada',
                'ativos_ferramental_retirada.created_at as dataRetirada',
                'ativos_ferramental_retirada.updated_at as dataDevolucao',
                'funcionarios.nome as funcionario',
                'funcionarios.matricula as funcionario_matricula',
                'ativos_ferramental_retirada_item.id_ativo_externo as id_ferramenta_retirada'
            )

            ->join("funcionarios",  "ativos_ferramental_retirada.id_funcionario", "=", "funcionarios.id")
            ->join("ativos_ferramental_retirada_item", "ativos_ferramental_retirada.id", "=", "ativos_ferramental_retirada_item.id_retirada")

            ->get();

        $categorias = AtivoConfiguracao::where('id_relacionamento', '>', 0)->get();
        $situacoes = AtivosExternosStatus::all();
        $ativo_configuracoes = AtivoConfiguracao::where('id_relacionamento', '>', 0)->where('status', 'Ativo')->get();

        $empresas = CadastroEmpresa::all();

        return view('pages.ativos.externos.partials.list', compact('itensRetirados', 'obras', 'categorias', 'situacoes', 'ativo_configuracoes', 'ativos', 'countStatus', 'valorTotalAtivos', 'totalAtivos'));
    }


    public function report(Request $request)
    {
        $id_obra = Session::get('obra')['id'] ?? null;
        $search = request('search');
        $status = request('status_ferramentas');

        if ($id_obra == null) {
            // PESQUISAR EM TODAS AS OBRAS
            if ($request->status_ferramentas != null) {
                $ativos = AtivoExternoEstoque::with('configuracao', 'situacao')
                    ->where(function ($query) use ($status) {
                        $query->where('status', 'like', "%$status%");
                    })
                    ->when($search, function ($query) use ($search) {
                        $query->whereHas('configuracao', function ($query) use ($search) {
                            $query->where('titulo', 'like', "%$search%");
                        });
                    })
                    ->get();

                $countStatus = AtivoExternoEstoque::select(
                    'ativos_externos_status.id AS idStatus',
                    'ativos_externos.status AS statusExterno',
                    'ativos_externos_estoque.status AS statusAtivo',
                    'ativos_externos_status.titulo',
                    'ativos_externos_status.classe'
                )
                    ->selectRaw('COUNT(ativos_externos_estoque.status) AS totalStatus')
                    ->join('ativos_externos_status', 'ativos_externos_estoque.status', '=', 'ativos_externos_status.id')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('ativos_externos.titulo', 'like', "%$search%")
                    ->where('ativos_externos_estoque.status',  $status)
                    ->groupBy('ativos_externos_status.id', 'ativos_externos.status', 'ativos_externos_estoque.status', 'ativos_externos_status.titulo', 'ativos_externos_status.classe')
                    ->get();

                $totalAtivos = AtivoExternoEstoque::selectRaw('COUNT(ativos_externos_estoque.id) AS totalAtivos')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('ativos_externos_estoque.status',  $status)
                    ->where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();

                $valorTotalAtivos = AtivoExternoEstoque::select(DB::raw('SUM(CAST(valor AS DECIMAL(13, 2))) as somaValorTotalFerramentasObra'))
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('ativos_externos_estoque.status',  $status)
                    ->where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();
            } else {
                $ativos = AtivoExternoEstoque::where(function ($query) use ($search) {
                    $query->where('patrimonio', 'like', "%$search%")
                        ->orWhere('calibracao', 'like', "%$search%");
                })
                    ->orWhereHas('configuracao', function ($query) use ($search) {
                        $query->where('titulo', 'like', "%$search%");
                    })
                    ->with('configuracao', 'situacao')
                    ->get();

                // ******* CONTAGEM ***** POR STATUS POR OBRAS
                $countStatus = AtivoExternoEstoque::select(
                    'ativos_externos_status.id AS idStatus',
                    'ativos_externos.status AS statusExterno',
                    'ativos_externos_estoque.status AS statusAtivo',
                    'ativos_externos_status.titulo',
                    'ativos_externos_status.classe'
                )
                    ->selectRaw('COUNT(ativos_externos_estoque.status) AS totalStatus')
                    ->join('ativos_externos_status', 'ativos_externos_estoque.status', '=', 'ativos_externos_status.id')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('ativos_externos.titulo', 'like', "%$search%")
                    ->groupBy('ativos_externos_status.id', 'ativos_externos.status', 'ativos_externos_estoque.status', 'ativos_externos_status.titulo', 'ativos_externos_status.classe')
                    ->get();

                $totalAtivos = AtivoExternoEstoque::selectRaw('COUNT(ativos_externos_estoque.id) AS totalAtivos')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();

                $valorTotalAtivos = AtivoExternoEstoque::select(DB::raw('SUM(CAST(valor AS DECIMAL(13, 2))) as somaValorTotalFerramentasObra'))
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();
            }
        } else {
            // *******  PESQUISAR POR OBRAS  *******
            if ($request->status_ferramentas != null) {
                $ativos = AtivoExternoEstoque::where('id_obra', $id_obra)
                    ->with('configuracao', 'obra', 'situacao')
                    ->where(function ($query) use ($status, $id_obra) {
                        $query->where('status', 'like', "%$status%")
                            ->where('id_obra', $id_obra);
                    })
                    ->when($search, function ($query) use ($search) {
                        $query->whereHas('configuracao', function ($query) use ($search) {
                            $query->where('titulo', 'like', "%$search%");
                        });
                    })
                    ->get();

                $countStatus = AtivoExternoEstoque::select(
                    'ativos_externos_status.id AS idStatus',
                    'ativos_externos.status AS statusExterno',
                    'ativos_externos_estoque.status AS statusAtivo',
                    'ativos_externos_status.titulo',
                    'ativos_externos_status.classe'
                )
                    ->selectRaw('COUNT(ativos_externos_estoque.status) AS totalStatus')
                    ->join('ativos_externos_status', 'ativos_externos_estoque.status', '=', 'ativos_externos_status.id')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('id_obra', $id_obra)
                    ->where('ativos_externos.titulo', 'like', "%$search%")
                    ->where('ativos_externos_estoque.status',  $status)
                    ->groupBy('ativos_externos_status.id', 'ativos_externos.status', 'ativos_externos_estoque.status', 'ativos_externos_status.titulo', 'ativos_externos_status.classe')
                    ->get();

                $totalAtivos = AtivoExternoEstoque::selectRaw('COUNT(ativos_externos_estoque.id) AS totalAtivos')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('id_obra', $id_obra)
                    ->where('ativos_externos_estoque.status',  $status)
                    ->where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();

                $valorTotalAtivos = AtivoExternoEstoque::select(DB::raw('SUM(CAST(valor AS DECIMAL(13, 2))) as somaValorTotalFerramentasObra'))
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('id_obra', $id_obra)
                    ->where('ativos_externos_estoque.status',  $status)
                    ->where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();
            } else {
                $ativos = AtivoExternoEstoque::where('id_obra', $id_obra)
                    ->where(function ($query) use ($search, $id_obra) {
                        $query->where('patrimonio', 'like', "%$search%")
                            ->orWhere('calibracao', 'like', "%$search%")
                            ->where('id_obra', $id_obra);
                    })
                    ->orWhereHas('configuracao', function ($query) use ($search, $id_obra) {
                        $query->where('titulo', 'like', "%$search%")
                            ->where('id_obra', "{$id_obra}");
                    })
                    ->with('configuracao', 'obra', 'situacao')
                    ->get();

                // ******* CONTAGEM ***** POR STATUS POR OBRAS
                $countStatus = AtivoExternoEstoque::select(
                    'ativos_externos_status.id AS idStatus',
                    'ativos_externos.status AS statusExterno',
                    'ativos_externos_estoque.status AS statusAtivo',
                    'ativos_externos_status.titulo',
                    'ativos_externos_status.classe'
                )
                    ->selectRaw('COUNT(ativos_externos_estoque.status) AS totalStatus')
                    ->join('ativos_externos_status', 'ativos_externos_estoque.status', '=', 'ativos_externos_status.id')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('id_obra', $id_obra)
                    ->where('ativos_externos.titulo', 'like', "%$search%")
                    ->groupBy('ativos_externos_status.id', 'ativos_externos.status', 'ativos_externos_estoque.status', 'ativos_externos_status.titulo', 'ativos_externos_status.classe')
                    ->get();

                $totalAtivos = AtivoExternoEstoque::selectRaw('COUNT(ativos_externos_estoque.id) AS totalAtivos')
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('id_obra', $id_obra)
                    ->where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();

                $valorTotalAtivos = AtivoExternoEstoque::select(DB::raw('SUM(CAST(valor AS DECIMAL(13, 2))) as somaValorTotalFerramentasObra'))
                    ->join('ativos_externos', 'ativos_externos_estoque.id_ativo_externo', '=', 'ativos_externos.id')
                    ->whereNull('ativos_externos_estoque.deleted_at')
                    ->where('id_obra', $id_obra)
                    ->where('ativos_externos.titulo', 'like', "%$search%")
                    ->get();
            }
        }

        $notification = $this->ativoExternoEstoque_report->list($ativos, $totalAtivos, $valorTotalAtivos);
        return $notification;
    }



    public function historico(int $id)
    {

        $itensRetirados = FerramentalRetirada::select('ativos_ferramental_retirada')

            ->select(
                'ativos_ferramental_retirada.*',
                'ativos_ferramental_retirada.status as statusRetirada',
                'ativos_ferramental_retirada.created_at as dataRetirada',
                'ativos_ferramental_retirada.data_devolucao as dataDevolucao',
                'funcionarios.nome as funcionario',
                'funcionarios.matricula as funcionario_matricula',
                'ativos_ferramental_retirada_item.id_ativo_externo as id_ferramenta_retirada'
            )

            ->join("funcionarios",  "ativos_ferramental_retirada.id_funcionario", "=", "funcionarios.id")
            ->join("ativos_ferramental_retirada_item", "ativos_ferramental_retirada.id", "=", "ativos_ferramental_retirada_item.id_retirada")
            ->where('ativos_ferramental_retirada_item.id_ativo_externo', $id)
            ->orderByDesc("ativos_ferramental_retirada_item.created_at")
            ->get();

        return view('pages.ativos.externos.partials.historico', compact('itensRetirados'));
    }


    public function show(Request $request, $id)
    {

        //Váriaveis do QRcode
        $options = new QROptions;

        $options->version          = 7;
        $options->outputInterface  = QRFpdf::class;
        $options->scale            = 7;
        $options->fpdfMeasureUnit  = 'mm';
        $options->bgColor          = [222, 222, 222];
        $options->drawLightModules = true;
        $options->outputBase64     = true;
        $options->moduleValues     = [
            // finder
            QRMatrix::M_FINDER_DARK    => [63, 63, 255],    // dark (true)
            QRMatrix::M_FINDER_DOT     => [0, 63, 255],    // finder dot, dark (true)
            QRMatrix::M_FINDER         => [255, 255, 255], // light (false)
            // alignment
            QRMatrix::M_ALIGNMENT_DARK => [255, 0, 255],
            QRMatrix::M_ALIGNMENT      => [255, 255, 255],
            // timing
            QRMatrix::M_TIMING_DARK    => [255, 0, 0],
            QRMatrix::M_TIMING         => [255, 255, 255],
            // format
            QRMatrix::M_FORMAT_DARK    => [67, 191, 84],
            QRMatrix::M_FORMAT         => [255, 255, 255],
            // version
            QRMatrix::M_VERSION_DARK   => [62, 174, 190],
            QRMatrix::M_VERSION        => [255, 255, 255],
            // data
            QRMatrix::M_DATA_DARK      => [0, 0, 0],
            QRMatrix::M_DATA           => [255, 255, 255],
            // darkmodule
            QRMatrix::M_DARKMODULE     => [0, 0, 0],
            // separator
            QRMatrix::M_SEPARATOR      => [255, 255, 255],
            // quietzone
            QRMatrix::M_QUIETZONE      => [255, 255, 255],
        ];




        if (Session::get('obra')['id'] == null) {

            $itens = AtivoExternoEstoque::with('obra', 'situacao')->where('id', $id)->get();
            $detalhes = AtivoExterno::with('configuracao')->find($itens[0]->id_ativo_externo);
            $qrcodeEtiqueta = (new QRCode($options))->render(env('APP_URL') . "/admin/ativo/externo/detalhes/" . $id);
        } else {

            $itens = AtivoExternoEstoque::with('obra', 'situacao')
                ->where('id', $id)
                ->where('id_obra', Session::get('obra')['id'])
                ->get();

            $detalhes = AtivoExterno::with('configuracao')
                ->find($itens[0]->id_ativo_externo);

            $qrcodeEtiqueta = (new QRCode($options))->render(env('APP_URL') . "/admin/ativo/externo/detalhes/" . $id);
        }

        $img_ferramenta = AtivoExternoEstoque::with('obra', 'situacao')->where('id', $id)->get();

        return view('pages.ativos.externos.show', compact('detalhes', 'itens', 'id', 'img_ferramenta', 'qrcodeEtiqueta'));
    }


    public function create()
    {
        if (Session::get('obra')['id'] == null) {
            $obras = CadastroObra::orderByDesc('id')->get();
        } else {
            $obras = CadastroObra::where('id', Session::get('obra')['id'])->orderByDesc('id')->get();
        }

        $empresas = CadastroEmpresa::all();

        $ativo_configuracoes = AtivoConfiguracao::with('relacionamento')->where('status', 'Ativo')->get();

        return view('pages.ativos.externos.create', compact('ativo_configuracoes', 'obras', 'empresas'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'id_ativo_configuracao' => 'required',
                'titulo' => 'required',
                'quantidade' => 'required|min:1',
                'status' => 'required',
                'imagem' => 'required'
            ],
            [
                'id_ativo_configuracao.required' => 'É necessário selecionar uma Categoria',
                'titulo.required' => 'Preencha o Título do Ativo',
                'quantidade.required' => 'A quantidade não pode ser Zero ou Nula',
                'status.required' => 'Selecione o Status',
                'imagem.required' => 'Insira uma imagem'
            ]
        );

        /* Salvar Ativo */
        $externo = new AtivoExterno();
        $externo->id_ativo_configuracao = $request->id_ativo_configuracao;
        $externo->titulo = $request->titulo;
        $externo->marcaCalibra = $request->marcaCalibra ?? null;
        $externo->modeloCalibra = $request->modeloCalibra ?? null;
        $externo->n_serie = $request->n_serie ?? null;
        $externo->status = $request->status;
        $save_externo = $externo->save();

        /* Inclusão de Estoque - Item */
        $externo_estoque_item = new AtivoExternoEstoqueItem();


        $externo_estoque_item->id_ativo_externo = $externo->id;

        $externo_estoque_item->quantidade_estoque = $request->quantidade;
        $externo_estoque_item->quantidade_em_transito = 0;
        $externo_estoque_item->quantidade_em_operacao = 0;
        $externo_estoque_item->quantidade_com_defeito = 0;
        $externo_estoque_item->quantidade_fora_de_operacao = 0;
        $save_externo_estoque = $externo_estoque_item->save();

        /* Salvar Ativo Estoque */
        $externo_estoque_quantidade = $request->quantidade;

        /* Inclusão de Estoque  */
        for ($i = 1; $i <= $externo_estoque_quantidade; $i++) {

            if ($request->calibracao == "Sim" and $request->quantidade > 1) {
                echo 'Não pode cadastrar';
                exit;
            }
            /* Contagem de Patrimonio diante do Atual */
            $patrimonio = Configuracao::PatrimonioAtual();

            /* Dados para Salvar no Estoque */
            $externo_estoque = new AtivoExternoEstoque();
            $externo_estoque->id_ativo_externo = $externo->id;
            $externo_estoque->id_obra = $request->id_obra;
            $externo_estoque->patrimonio = Configuracao::PatrimonioSigla() . $patrimonio;
            $externo_estoque->valor = $request->valor;

            $externo_estoque->calibracao = $request->calibracao;
            $externo_estoque->detalhes = $request->detalhes ?? null;

            $externo_estoque->status = 4; // Em Estoque

            //SCRIPT PARA SUBIR ARQUIVO NA PASTA 'public/storage/imagem_ativo'
            $nome_img = preg_replace('/[ -]+/', '-', @$_FILES['imagem']['name']);
            $caminho = public_path('storage/imagem_ativo/' . $nome_img);
            if (@$_FILES['imagem']['name'] == "") {
                $imagem = "";
            } else {

                $imagem = $nome_img;
            }

            $imagem_temp = @$_FILES['imagem']['tmp_name'];

            $ext = pathinfo($imagem, PATHINFO_EXTENSION);

            // if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == '') {

            move_uploaded_file($imagem_temp, $caminho);
            $externo_estoque->imagem = $nome_img;

            // } else {

            // echo 'Extensão de Imagem não permitida!';

            //   exit();
            //}

            $externo_estoque->save();
        }



        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | ADD ATIVO EXTERNO: ' . $externo_estoque->patrimonio);

        if ($save_externo && $save_externo_estoque) {
            return redirect()->route('ativo.externo')->with('success', 'Novos ativos foram inseridos no estoque.');
        } else {
            return redirect()->route('ativo.externo')->with('fail', 'Não foi possível processar os ativos solicitados. Fale com seu supervisor.');
        }
    }




    public function insert(AtivoExterno $ativo)
    {
        $obras = CadastroObra::all();

        $empresas = CadastroEmpresa::all();

        $ativo_configuracoes = AtivoConfiguracao::where('id_relacionamento', '>', 0)->get();

        return view('pages.ativos.externos.insert', compact('ativo_configuracoes', 'obras', 'ativo', 'empresas'));
    }

    public function insertStore(Request $request)
    {
        // dd($request->all());

        $request->validate(
            [
                'id_ativo_configuracao' => 'required',
                'titulo' => 'required',
                'quantidade' => 'required|min:1',
                'status' => 'required'
            ],
            [
                'id_ativo_configuracao.required' => 'É necessário selecionar uma Categoria',
                'titulo.required' => 'Preencha o Título do Ativo',
                'quantidade.required' => 'A quantidade não pode ser Zero ou Nula',
                'status.required' => 'Selecione o Status'
            ]
        );

        /* Salvar Ativo Estoque */
        $externo_estoque_quantidade = $request->quantidade;

        /* Inclusão de Estoque  */
        for ($i = 1; $i <= $externo_estoque_quantidade; $i++) {

            /* Contagem de Patrimonio diante do Atual */
            $patrimonio = Configuracao::PatrimonioAtual();

            /* Dados para Salvar no Estoque */
            $externo_estoque = new AtivoExternoEstoque();
            $externo_estoque->id_ativo_externo = $request->id_ativo_externo;
            $externo_estoque->id_obra = $request->id_obra;
            $externo_estoque->patrimonio = Configuracao::PatrimonioSigla() . $patrimonio;
            $externo_estoque->valor = $request->valor ?? 0;
            $externo_estoque->calibracao = $request->calibracao;
            $externo_estoque->status = 4; // Em Estoque
            $externo_estoque->save();
        }

        $save = AtivoExternoEstoqueItem::where('id_ativo_externo', $request->id_ativo_externo)->increment('quantidade_estoque', $request->quantidade);

        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | ADD ATIVO EXTERNO: ' . $externo_estoque->patrimonio);

        if ($save) {
            return redirect()->route('ativo.externo.detalhes', $request->id_ativo_externo)->with('success', 'Novos ativos foram inseridos no estoque.');
        } else {
            return redirect()->route('ativo.externo')->with('fail', 'Não foi possível processar os ativos solicitados. Fale com seu supervisor.');
        }
    }

    public function edit(Request $request, $id)
    {
        $estoques = AtivoExternoEstoque::with('obra', 'situacao', 'ativo_externo')->where('id', $id)->get();
        if (Session::get('obra')['id'] == null) {
            $obras = CadastroObra::orderByDesc('id')->get();
        } else {
            $obras = CadastroObra::where('id', Session::get('obra')['id'])->orderByDesc('id')->get();
        }

        $categorias = AtivoConfiguracao::where('id_relacionamento', '>', 0)->get();
        $situacoes = AtivosExternosStatus::all();
        $ativo_configuracoes = AtivoConfiguracao::with('relacionamento')->where('status', 'get_ativo_configuracoes')->get();
        $empresas = CadastroEmpresa::all();

        $calibrados = AtivoExterno::where('id', 539)->get();
        /* dd($calibrados);*/

        return view('pages.ativos.externos.edit', compact('ativo_configuracoes', 'estoques', 'obras', 'categorias', 'situacoes', 'empresas', 'calibrados'));
    }



    public function update(Request $request, $id)
    {

        if (!$save = AtivoExternoEstoque::find($id)) {

            return redirect()->route('ativo.externo.editar')->with('fail', 'Problemas para localizar o ativo.');
        }


        $userLog = Auth::user()->email;
        Log::channel('main')->info($userLog . ' | EDIT ATIVOS INTERNOS: ' . $save->patrimonio);
        //dd( $request->detalhes);
        $data = $request->all();
        $salvando = AtivoExterno::where('id', $request->id_ativo_externo);
        $externo['titulo'] = $request->titulo;
        $externo['marcaCalibra'] = $request->marcaCalibra;
        $externo['modeloCalibra'] = $request->modeloCalibra;
        $externo['n_serie'] = $request->n_serie;


        $salvando->update($externo);

        $atualiza['id_obra'] = $request->id_obra;
        $atualiza['valor'] = $request->valor;
        $atualiza['calibracao'] = $request->calibracao;

        $dateDeleted = date('Y-m-d H:i:s');


        if ($request->status == 9) {
            $atualiza['data_descarte'] = $dateDeleted;
            $atualiza['status'] = 9;
        } else {
            $atualiza['status'] = $request->status;
        }

        //SCRIPT PARA SUBIR ARQUIVO NA PASTA 'public/storage/imagem_ativo'
        $nome_img = preg_replace('/[ -]+/', '-', @$_FILES['imagem']['name']);
        $caminho = public_path('storage/imagem_ativo/' . $nome_img);
        if (@$_FILES['imagem']['name'] == "") {
            $imagem = "";
        } else {

            $imagem = $nome_img;
        }

        $imagem_temp = @$_FILES['imagem']['tmp_name'];

        $ext = pathinfo($imagem, PATHINFO_EXTENSION);

        //if ($ext == 'png' or $ext == 'jpg' or $ext == 'jpeg' or $ext == 'gif' or $ext == '') {

        move_uploaded_file($imagem_temp, $caminho);
        $save['imagem'] = $nome_img ?? $request->imagem;

        $save['detalhes'] = $request->detalhes ?? null;
        //} else {

        // echo 'Extensão de Imagem não permitida!';

        // exit();
        // }


        $save->update($atualiza);

        return redirect()->route('ativo.externo')->with('success', 'Registro atualizado com sucesso.');
    }

    public function destroy($id)
    {
        // Encontra o ativo pelo ID
        $ativo = AtivoExternoEstoque::findOrFail($id);

       // dd($ativo);

        // Caminho da imagem a ser deletada
        $nome_img = $ativo->imagem; // Altere para o nome do campo que contém o nome da imagem
        $imagePath = public_path('storage/imagem_ativo/' . $nome_img);


        if ($nome_img) {
            // Verifica se o arquivo existe e o apaga
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        // Apaga o registro do banco de dados
        $ativo->delete();

        // Retorna uma resposta de sucesso
        return redirect()->route('ativo.externo')->with('success', 'Registro excluído!!!');
    }





    public function anexo($id_ativo_externo = null)
    {
        if (!$id_ativo_externo) {
            return redirect()->route('ativo.externo.index')->with('fail', 'Problemas para localizar o ativo.');
        }

        $anexo = Anexo::where('id_item', $id_ativo_externo)->get();

        if (!$anexo) {
            return [];
        }

        if ($anexo) {
            return view('components.anexo.lista_anexo', compact('anexo'));
        }
    }

    public function anexoRelatorioDescarte($id_ativo_externo = null)
    {
        if (!$id_ativo_externo) {
            return redirect()->route('ativo.externo.index')->with('fail', 'Problemas para localizar o ativo.');
        }

        $anexo = Anexo::where('id_item', $id_ativo_externo)
            ->where('id_method', 3)
            ->get();

        if (!$anexo) {
            return [];
        }

        if ($anexo) {
            return view('components.anexo.lista_anexo', compact('anexo'));
        }
    }

    public function anexoDocsAtivos($id_ativo_externo = null)
    {
        if (!$id_ativo_externo) {
            return redirect()->route('ativo.externo.index')->with('fail', 'Problemas para localizar o ativo.');
        }

        $anexo = Anexo::where('id_item', $id_ativo_externo)
            ->where('nome_modulo', "doc_ativo_externo")
            ->get();

        if (!$anexo) {
            return [];
        }

        if ($anexo) {
            return view('components.anexo.lista_anexo', compact('anexo'));
        }
    }


    public function indexCalibracao($id)
    {

        if (!$id) {
            return redirect()->route('ativo.externo.index')->with('fail', 'Problemas para localizar o ativo.');
        }

        $anexo = Anexo::where('id_item', $id)->get();
        $dadosAtivoExterno = AtivoExternoEstoque::with('obra', 'situacao', 'ativo_externo')->where('id', $id)->get();

        if (!$anexo) {
            return [];
        }

        if ($anexo) {
            return view('pages.ativos.externos.partials.index', compact('anexo', 'dadosAtivoExterno'));
        }
    }


    public function editCalibracao($id)
    {
        $calibracao = Anexo::find($id);

        return view('pages.ativos.externos.partials.calibracao', compact('calibracao'));
    }

    public function updateCalibracao()
    {
        if (!$save = Anexo::find($id)) {

            return redirect()->route('ativo.externo')->with('fail', 'Problemas para localizar o registro.');
        }
    }


    public function download()
    {
        $obra = Session::get('obra')['codigo_obra'];

        // Sanitize the file name by removing special characters
        $obra = preg_replace('/[^A-Za-z0-9\-]/', '_', $obra);

        if ($obra == null) {


            return Storage::download("public/report/ativo_externo/Rel-ativos-externos-" . date("d-m-Y H") . ".xlsx");
        } else {

            return Storage::download("public/report/ativo_externo/Rel-ativos-externos-" . $obra . date("d-m-Y H") . ".xlsx");
        }
    }
}
