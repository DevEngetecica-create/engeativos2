@extends('dashboard')
@section('title', 'Ativos Externos - Detalhes')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Detalhes do Ativo Externo
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Ativos <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h3 class="page-title">
        <a href="{{ route('ativo.externo.inserir', $detalhes->id) }}">
            <button class="btn btn-sm btn-danger">Incluir Pertencentes</button>
        </a>
        <a href="{{ url('admin/ativo/externo') }}">
            <button class="btn btn-sm btn-light">Listar Todos Ativos</button>
        </a>
    </h3>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <div class="col-lg-12 grid-margin stretch-card">
                    <div class="card">
                        <div class="card-body pb-1 pt-1 pt-2">
                            @if (session('mensagemFail'))
                                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                                    <strong>{{ session('mensagemFail') }}</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('mensagem'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>{{ session('mensagem') }}</strong>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <h4 class="card-title">Item</h4>
                            <hr class="m-0 mb-2">

                            <dl class="row m-0 p-0">
                                <dt class="col-sm-1">QRCode</dt>
                                <dd class="d-flex col-12">
                                    <div class="card mb-3 shadow" style="max-width: 400px; max-height:200px">
                                        <div class="row no-gutters">
                                            <div class="d-flex align-items-center col-md-3 m-3" id="qrcode"></div>
                                            <div class="col-md-8">
                                                <div class="card-body p-3">
                                                    <h5 class="card-title">ETIQUETA</h5>
                                                    <p class="card-text"><span>{{ @$detalhes->titulo }}</span></p>
                                                    <p class="card-text">
                                                        <small class="text-muted">QRCode para Visualizar a página de Detalhes do Equipamento</small>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mx-5">
                                        <a data-bs-toggle="modal" data-bs-target="#anexarDocAtivoExterno" class="ferramenta dropdown-item bg-success rounded" href="javascript:void(0)" data-id="{{ $detalhes->id }}">
                                            <span class='btn btn-success btn-sm ml-1'>
                                                <i class="mdi mdi-upload"></i>Anexar arquivos
                                            </span>
                                        </a>
                                    </div>

                                    @php
                                        $imagemFerramenta = $img_ferramenta->last();
                                    @endphp

                                    <div class="form-group">
                                        @if ($imagemFerramenta->imagem ?? null)
                                            <img src="{{ url('storage/imagem_ativo') }}/{{ $imagemFerramenta->imagem }}" id="target" class="img-thumbnail" style="width: 500px; height: 300px;">
                                        @else
                                            <img src="{{ url('storage/imagem_ativo/nao-ha-fotos.png') }}" id="target" class="img-thumbnail" style="width: 500px; height: 300px;">
                                        @endif
                                    </div>
                                </dd>
                            </dl>
                            <table class="table table-striped table-bordered" width="100%">
                                <thead>
                                    <tr>
                                        <th> ID </th>
                                        <th> Categoria </th>
                                        <th> Item </th>
                                        <th> Data de Inclusão </th>
                                        <th> Status </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>{{ $detalhes->id }}</td>
                                        <td>{{ $detalhes->categoria->titulo ?? 'Sem registro' }}</td>
                                        <td>{{ $detalhes->titulo }}</td>
                                        <td>{{ Tratamento::datetimeBr($detalhes->created_at) }}</td>
                                        <td>
                                            <div class="badge badge-warning">{{ $detalhes->status }}</div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="card-body mt-3">
                            <h4 class="card-title">Itens pertencentes ao Estoque</h4>
                            <hr>
                            <table id="tabelaDetalhes" class="table-hover table-striped yajra-datatable table pt-4" id="tabela">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Patrimônio</th>
                                        <th>Obra Atual</th>
                                        <th>Valor do Item</th>
                                        <th>Calibração?</th>
                                        <th>Data de Descarte</th>
                                        <th>Data de Inclusão</th>
                                        <th class="text-center"> Situação</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($itens as $item)
                                        <tr>
                                            <td>{{ $item->id }} <img src="{{ url('storage/imagem_ativo') }}/{{ $item->imagem }}" id="target" class="img-thumbnail" style="width: 30px; height: 30px;"></td>
                                            <td class="text-center"><span class="">{{ $item->patrimonio }}</span></td>
                                            <td class="text-center"><span class="">{{ $item->obra->codigo_obra ?? '-' }}</span></td>
                                            <td class="text-right">R$ {{ $item->valor }}</td>
                                            <td class="text-center">
                                                @if ($item->calibracao == 0)
                                                    Não
                                                @elseif ($item->calibracao == 1)
                                                    Sim
                                                @else
                                                    NULO
                                                @endif
                                            </td>
                                            <td>{{ $item->data_descarte }}</td>
                                            <td>{{ Tratamento::datetimeBr($item->created_at) }}</td>
                                            <td class="text-center">
                                                <span class="bg-{{ $item->situacao->classe ?? '' }} px-2 py-1 rounded text-white">{{ $item->situacao->titulo ?? '' }}</span>
                                                <button id="imprimirEtiqueta" class="mx-2 btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_etiqueta">
                                                    <i class="mdi mdi-shredder"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th>#</th>
                                        <th>Patrimônio</th>
                                        <th>Obra Atual</th>
                                        <th>Valor do Item</th>
                                        <th>Calibração?</th>
                                        <th>Data de Descarte</th>
                                        <th>Data de Inclusão</th>
                                        <th>Situação</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal_etiqueta" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalToggleLabel">Etiqueta</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" >
                
                <div class="p-1">
                    <div  class="card border" id="etiquetasIndividuais" style="max-width: 120mm; height:45mm">
                       
                        <div class="row no-gutters">
                            <div class="col-3 m-3" id="qrcode-modal"></div>
                            <div class="col ">
                                <div class="card-body p-3">
                                    <h4><strong>Patrimonio: <span id="patrimonio"></span></strong></h5>
                                    <h4><strong>{{ @$detalhes->titulo }}</strong></h4>
                                    <h4><strong>www.engetecnica.com.br</strong></h5>
                                </div>
                            </div>
                        </div>
                                  
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" id="print-button-Individual" class="btn btn-secondary">Imprimir</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/print-js@1.6.0/dist/print.min.js"></script>

<script>
    $(document).ready(function() {
        // Gerar o QR code na view inicial
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ env('APP_URL') . '/admin/ativo/externo/detalhes/' . $id }}",
            width: 105,
            height: 105
        });

        $('#print-button-Individual').on('click', function() {
            captureAndPrint();
        });

        function captureAndPrint() {
            html2canvas(document.getElementById('etiquetasIndividuais')).then(function(canvas) {
                printJS({
                    printable: canvas.toDataURL('image/png'),
                    type: 'image'
                });
            });
        }

        // Gerar o QR code na modal
        $('#modal_etiqueta').on('show.bs.modal', function() {
            var qrcodeElement = document.getElementById("qrcode-modal");
            qrcodeElement.innerHTML = ""; // Limpar o conteúdo anterior
            new QRCode(qrcodeElement, {
                text: "{{ env('APP_URL') . '/admin/ativo/externo/detalhes/' . $detalhes->id }}",
                width: 100,
                height: 100
            });
        });

        // Encontre todos os botões "Detalhes"
        var detalhesButtons = document.querySelectorAll('#imprimirEtiqueta');

        // Encontre a <div> onde os detalhes serão exibidos
        var divDetalhes = document.getElementById('etiquetasIndividuais');

        // Adicione um evento de clique a cada botão "Detalhes"
        detalhesButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                // Encontre a linha pai (tr) da célula de botão clicada
                var row = button.closest('tr');

                // Obtenha os dados da linha
                var id = row.cells[0].textContent;
                var patrimonio = row.cells[1].textContent;
                var titulo = row.cells[2].textContent;

                var spanPatrimonio = document.getElementById('patrimonio');
                spanPatrimonio.textContent = patrimonio;

                // Preencha a <div> com os dados da linha
                /* divDetalhes.innerHTML = `
                    <p>Patrimônio: ${patrimonio}</p>
                    <p>Título: ${titulo}</p>
                `; */
            });
        });
    });
</script>
@endsection

