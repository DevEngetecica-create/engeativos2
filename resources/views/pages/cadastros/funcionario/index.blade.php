@extends('dashboard')
@section('title', 'Funcionários')
@section('content')

<div class="row">
    <div class="col-2 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-center">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-access-point-network menu-icon"></i>
            </span> Funcionários
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Cadastro <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>
</div>

<hr>

<form method="post" class="form" enctype="multipart/form-data" id="form">
    @csrf
    <div class="row my-4">
        <div class="col-2">
            <h3 class="page-title text-left">
                <a href="{{ route('cadastro.funcionario.adicionar') }}">
                    <span class="btn btn-sm btn-success shadow p-2">Novo Registro</span>
                </a>
            </h3>
        </div>
        <div class="col-10">
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <input type="text" class="form-control shadow" name="funcionario" placeholder="Pesquisar categoria" value="{{ request()->funcionario }}">
                    <input type="hidden" id="page" name="page" value="0">
                </div>
                <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                    <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i class="mdi mdi-file-search-outline mdi-24px"></i></button>

                    <a href="{{ url('admin/cadastro/funcionario') }}" title="Limpar pesquisa">
                        <span class="btn btn-warning btn-sm py-0 shadow"><i class="mdi mdi-delete-outline mdi-24px"></i></span>
                    </a>
                </div>
                <div class="col-1 text-left m-0">

                </div>
            </div>
        </div>
    </div>
</form>


<div class="row">
    <div class="col-lg-12 grid-margin stretch-card" id="conteudo">
        
        {{--Tabela--}}

    </div>
</div>

 <!-- Modal para exibir a etiqueta -->
    <div id="modal_funcionario" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
        style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Etiqueta do Funcionário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <div class="card-body" id="etiqueta">

                        <div class="p-1">
                            <div class="card border" id="etiquetasIndividuais" style="max-width: 120mm; height:45mm">
                                <div class="row no-gutters">                                    
                                    <div class="col ">
                                        <div class="card-body p-3">
                                            <div class="col-3 m-3" id="qrcode_cracha"></div>
                                            <img src="{{ URL::asset('build/images/usuarios/cracha_v00')}}" >
                                            <strong>
                                                <h3><span id="funcionario"></span></h3>
                                            </strong>
                                            <strong>
                                                <h3>Função: <span id="funcao"></span></h3>
                                            </strong>                                           
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="print_etiqueta">Imprimir</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


 <!-- Modal para exibir o cracha -->
    <div id="modal_cracha" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel">Crachá do Funcionário</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
                </div>
                <div class="modal-body">
                    <div class="card-body" id="cracha">

                        <div class="p-1">
                            <div class="card border" id="crachaIndividuais">

                                <div class="row no-gutters">
                                    <div class="col-3 m-3" id="qrcode"></div>
                                    <div class="col ">
                                        <div class="card-body p-3">
                                            <strong>
                                                <h3><span id="nome_funcionario"></span></h3>
                                            </strong>
                                            <strong>
                                                <h3>Função: <span id="cracha_funcao"></span></h3>
                                            </strong>
                                            
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="print_cracha">Imprimir</button>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/print-js@1.6.0/dist/print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            carregarTabela(0);
            
            
        });

        $(document).on('click', '.paginacao a', function(e) {
            e.preventDefault();
            var pagina = $(this).attr('href').split('page=')[1];
            carregarTabela(pagina);
        });

        $(document).on('keyup submit', '.form', function(e) {
            e.preventDefault();
            carregarTabela(0);
        });

        function carregarTabela(pagina) {
            $('.loader').html('<div class="spinner-border m-0 p-0" role="status"><span class="sr-only"></span></div>');
            
            $('#page').val(pagina);
            var dados = $('#form').serialize();
            $.ajax({
                url: "/admin/cadastro/funcionario/list",
                method: 'GET',
                data: dados
            }).done(function(data) {
                
                $('#conteudo').html(data);
                
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                  return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            
                adicionarEventosEtiquetas(); // Adicionar eventos após carregar a tabela
            });
        }

        function adicionarEventosEtiquetas() {
            $('.btn-success[id="etiqueta_funcionario"]').off('click').on('click', function() {
                var id = $(this).data('id');
                var url ="https://sga-engeativos.com.br/detalhes/funcionario/" + id;
                var qrcodeContainer = document.getElementById('qrcode');

                // Limpar QRCode anterior e link anterior, se existirem
               qrcodeContainer.innerHTML = '';

                // Gerar novo QRCode
                new QRCode(qrcodeContainer, {
                    text: url,
                    width: 140,
                    height: 140,
                    correctLevel: QRCode.CorrectLevel.H
                });


            });

            $('#print_etiqueta').on('click', function() {
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
            // Encontre todos os botões "Detalhes"
            var detalhesButtons = document.querySelectorAll('#etiqueta_funcionario');
            // Adicione um evento de clique a cada botão "Detalhes"
            detalhesButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    // Encontre a linha pai (tr) da célula de botão clicada
                    var row = button.closest('tr');

                    // Obtenha os dados da linha
                    var id = row.cells[0].textContent;
                    var funcionario = row.cells[3].textContent;
                    var funcao = row.cells[4].textContent;

                    var spanfuncionario = document.getElementById('funcionario');
                    spanfuncionario.textContent = funcionario;

                    var spanfuncionario = document.getElementById('funcao');
                    spanfuncionario.textContent = funcao;

                    // Preencha a <div> com os dados da linha
                    /* divDetalhes.innerHTML = `
                    <p>Patrimônio: ${patrimonio}</p>
                    <p>Título: ${titulo}</p>
                `; */
                });
            });


            /*    $('#print_etiqueta').off('click').on('click', function() {
                   var canvas = document.getElementById('etiquetaCanvas');
                   var printWindow = window.open('', '', 'width=600,height=400');
                   printWindow.document.write('<img src="' + canvas.toDataURL() + '" />');
                   printWindow.document.close();
                   printWindow.print();
               }); */
        }
    </script>

@endsection
