@extends('dashboard')
@section('title', 'Ativos Internos')
@section('content')

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary me-2 text-white">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Ativos Internos
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
            @if (session()->get('usuario_vinculo')->id_nivel <= 2)
                <a href="{{ route('ativo.interno.create') }}">
                    <button class="btn btn-sm btn-danger">Cadastrar</button>
                </a>
            @endif
        </h3>
    </div>

    <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">

                    <table class="table-hover table-striped table" id="lista-simples">
                        <thead>
                            <tr>
                                <th width="8%">ID</th>
                                <th>Obra</th>
                                <th>Patrimônio</th>
                                <th>Nº de série</th>
                                <th>Título</th>
                                <th>Marca</th>
                                <th>Valor</th>
                                <th>Inclusão</th>
                                <th>Situação</th>
                                @if (session()->get('usuario_vinculo')->id_nivel <= 2)
                                    <th width="10%">Ações</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($ativos as $ativo)
                                <tr>
                                    <td class="text-center align-middle"><span class="badge badge-dark">{{ $ativo->id }}</span></td>
                                    <td class="align-middle">{{ $ativo->obra->razao_social }}</td>
                                    <td class="align-middle">{{ $ativo->patrimonio }}</td>
                                    <td class="align-middle">{{ $ativo->numero_serie }}</td>
                                    <td class="align-middle">{{ $ativo->titulo }}</td>
                                    <td class="align-middle">{{ $ativo->marca }}</td>
                                    <td class="align-middle">{{ Tratamento::currencyFormatBr($ativo->valor_atribuido) }}</td>
                                    <td class="align-middle">{{ Tratamento::datetimeBr($ativo->created_at) }}</td>
                                    <td class="text-center align-middle">
                                        @if ($ativo->status == 1)
                                            <span class="badge badge-success">Ativo</span>
                                        @elseif ($ativo->status == 0)
                                            <span class="badge badge-danger">Inativo</span>
                                        @else
                                            <span class="badge badge-danger">Inativo</span>
                                        @endif
                                    </td>
                                    @if (session()->get('usuario_vinculo')->id_nivel <= 2)
                                        <td class="d-flex gap-2 align-middle">
                                            
                                            <a  class="m-0" href="{{ route('ativo.interno.edit', $ativo->id) }}">
                                                <button class="btn btn-warning btn-sm" >
                                                    <i class="mdi mdi-pencil mdi-24px"></i>
                                                </button>
                                            </a>
                                            
                                            <a  class="m-0" href="{{ route('ativo.interno.show', $ativo->id) }}">
                                                <button class="btn btn-info btn-sm" >
                                                    <i class="mdi mdi-eye mdi-24px"></i>
                                                </button>
                                            </a>

                                            <form class="m-0" action="{{ route('ativo.interno.destroy', $ativo->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button class="btn btn-danger btn-sm" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                                    <i class="mdi mdi-delete mdi-24px"></i>
                                                </button>
                                            </form>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    

@endsection

<script src="https://code.jquery.com/jquery-3.6.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.js"></script>
<script src="https://cdn.rawgit.com/davidshimjs/qrcodejs/gh-pages/qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.5.0-beta4/html2canvas.min.js"></script>

<script>
    $(document).ready(function() {
        document.getElementById('print-button-Individual').addEventListener('click', function() {
            // Captura o conteúdo da etiqueta como uma imagem usando html2canvas imagemQRCode


            html2canvas(document.getElementById('etiquetasIndividuais')).then(function(canvas) {
                // Imprime a imagem usando Print.js
                printJS({
                    printable: canvas.toDataURL('image/png'),
                    type: 'image',
                });
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
                /*  divDetalhes.innerHTML = `    
                     <p>Patrimônio: ${patrimonio}</p>
                     <p>Título: ${titulo}</p>
                 `; */
            });
        });

    });
</script>
