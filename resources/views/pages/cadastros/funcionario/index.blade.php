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
                        <input type="text" class="form-control shadow" name="funcionario"
                            placeholder="Pesquisar categoria" value="{{ request()->funcionario }}">
                        <input type="hidden" id="page" name="page" value="0">
                    </div>
                    <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                        <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i
                                class="mdi mdi-file-search-outline mdi-24px"></i></button>

                        <a href="{{ url('admin/cadastro/funcionario') }}" title="Limpar pesquisa">
                            <span class="btn btn-warning btn-sm py-0 shadow"><i
                                    class="mdi mdi-delete-outline mdi-24px"></i></span>
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

            {{-- Tabela --}}

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
                                            <div class="col-3 m-3" id="qrcode"></div>

                                            <h3><strong><span id="funcionario"></span></strong></h3>

                                            <h3><strong>Função: <span id="funcao"></span></strong></h3>

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

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Barlow+Condensed:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

        @import url('https://fonts.googleapis.com/css2?family=Barlow+Bold:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');


        #crachaIndividuais {
            background-image: url("{{ url('build/images/usuarios/cracha_v00.jpg') }}");
            background-size: cover;
            background-position: center;
            width: 733px !important;
            height: 1006px !important;
            margin: 0px !important;
            padding: 0px !important;
            left: 0% !important;
            top: 0% !important;
            object-fit: cover;
            position: relative;
        }

        #qrcode_cracha {
            position: absolute;
            bottom: 8px;
            width: 20%;
            height: 30px;
            padding: 0;
            top: 72%;
            left: 10%;
        }

        #qrcode_cracha img {
            position: absolute;
            width: 140px;
            height: 140px;
        }

        #informacoes_cracha {
            position: absolute;
            width: auto;
            left: 35%;
            top: 72%;
            text-align: start;
            padding: 0px;
            font-family: "Barlow Condensed", sans-serif;
            font-weight: 800;
            font-style: normal;
            font-size: 100px;
        }

        #informacoes_cracha h3 {
            font-family: "Barlow Condensed", sans-serif;
            font-style: normal;
            font-weight: 800;
            font-size: 55px;
            color: black;
            letter-spacing: -3px;
        }

        #informacoes_cracha h4 {
            font-family: "Barlow Condensed", sans-serif;
            font-style: italic;
            font-weight: 700;
            font-size: 50px;
            color: #ff5205;
        }

        .image_usuario {
            position: absolute;
            top: 22.8%;
            left: 28.4%;
        }

        .image_usuario img {
            width:63.5%
        }



        .imprimir {
            position: fixed;
            display: inline;
            top: 5%;
            right: 15%;
            z-index: 5;
            background-color: #ff5205;
            padding: 20px;
            border-radius: 6px;

        }

        .save_cracha {
            position: fixed;
            display: inline;
            top: 5%;
            right: 5%;
            z-index: 5;
            background-color: #ff5205;
            padding: 20px;
            border-radius: 6px;

        }

        @media print {

            @page {
                /* Define o tamanho como A4 */
                margin: 0px !important;
                /* Remove as margens da página */
                padding: 0px !important;
                left: 0px !important;
                width: 52mm;
                height: 84mm;

            }

        }
    </style>

    <!-- The Modal -->

    <div class="modal fade" id="modal_cracha" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Crachás</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="p-0" id="crachaIndividuais">
                        <div class="image_usuario p-0 m-0">
                            <img id="image_cracha" src="" alt="">
                        </div>

                        <div class="p-0 m-0 " id="qrcode_cracha"></div>

                        <div class="p-0 m-0" id="informacoes_cracha">
                            <h3 class="text-uppercase"><span id="nome_funcionario"></span></h3>
                            <h4><span id="cracha_funcao"></span></h4>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="imprimir">
                        <button type="button" class="btn btn-success" id="print_cracha">Imprimir</button>
                    </div>
                    <div class="salvar">
                        <button type="button" class="btn btn-primary" id="save_cracha">Salvar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/html2canvas@1.4.1/dist/html2canvas.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/print-js@1.6.0/dist/print.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script></script>
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
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })

                // Adicionar eventos após carregar a tabela

                adicionarEventosEtiquetas();
                adicionarEventosCrachas()

                
            });
        }

        function adicionarEventosEtiquetas() {
            $('.btn-success[id="etiqueta_funcionario"]').off('click').on('click', function() {
                var id = $(this).data('id');
                var url = "https://sga-engeativos.com.br/detalhes/funcionario/" + id;
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

                    var sem_imagem =
                        ` <img id="image_cracha" src="{{ URL::asset('build/images/usuarios/user-dummy-img.jpg') }}" alt="">`;

                    var image_cracha =
                        ` <img id="image_cracha" src="{{ URL::asset('build/images/usuarios/`+id+`/samuel.melo.jpg') }}" alt="">`;

                    var spanfuncionario = document.getElementById('funcionario');
                    spanfuncionario.textContent = funcionario;

                    var spanfuncionario = document.getElementById('funcao');
                    spanfuncionario.textContent = funcao;
                });
            });

        }

        function adicionarEventosCrachas() {
            $('.btn-warning[id="cracha_funcionario"]').off('click').on('click', function() {
                var id = $(this).data('id');
                var image_usuario = $(this).data('image');
                var url = "https://sga-engeativos.com.br/detalhes/funcionario/" + id;
                var qrcodeContainer = document.getElementById('qrcode_cracha');

                // Limpar QRCode anterior e link anterior, se existirem
                qrcodeContainer.innerHTML = '';

                // Gerar novo QRCode
                new QRCode(qrcodeContainer, {
                    text: url,

                    correctLevel: QRCode.CorrectLevel.H
                });

                // Definir o caminho da imagem com base na existência de 'image_usuario'
                var imagePath = image_usuario ? `{{ URL::asset('build/images/users/${id}/${image_usuario}') }}` :
                    `{{ URL::asset('build/images/usuarios/user-dummy-img.jpg') }}`;

                // Atualize o atributo 'src' da imagem de uma única vez
                document.getElementById('image_cracha').setAttribute('src', imagePath);




            });

            $('#print_cracha').on('click', function() {
                captureAndPrintCracha();
            });

            function captureAndPrintCracha() {
                html2canvas(document.getElementById('crachaIndividuais')).then(function(canvas) {
                    printJS({
                        printable: canvas.toDataURL('image/png'),
                        type: 'image'
                    });
                });
            }

            $('#save_cracha').on('click', function() {
                captureAndSaveCracha();
            });

            function captureAndSaveCracha() {
                html2canvas(document.getElementById('crachaIndividuais'), {
                    width: 732, // Largura desejada
                    height: 1006, // Altura desejada
                    scale: 1 // Mantém a proporção
                }).then(function(canvas) {
                    var image = canvas.toDataURL('image/png');
                    var link = document.createElement('a');
                    link.href = image;
                    link.download = 'cracha.png'; // Nome do arquivo salvo
                    link.click(); // Inicia o download
                });
            }


            // Encontre todos os botões "Detalhes"
            var detalhesButtonsCracha = document.querySelectorAll('#cracha_funcionario');
            // Adicione um evento de clique a cada botão "Detalhes"
            detalhesButtonsCracha.forEach(function(button1) {
                button1.addEventListener('click', function() {
                    // Encontre a linha pai (tr) da célula de botão clicada
                    var row = button1.closest('tr');

                    // Obtenha os dados da linha
                    var id_cracha = row.cells[0].textContent;
                    var funcionario_cracha = formatEmailName(row.cells[6].textContent);
                    var funcao_cracha = row.cells[4].textContent;

                    var nome_funcionario_cracha = document.getElementById('nome_funcionario');
                    nome_funcionario_cracha.textContent = funcionario_cracha;

                    var funcao_funcionario_cracha = document.getElementById('cracha_funcao');
                    funcao_funcionario_cracha.textContent = funcao_cracha;
                   

                });
            });

            // Get the modal
            var modal = document.getElementById("modal_cracha");

            // Get the button that opens the modal
            var btn = document.getElementById("cracha_funcionario");

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks the button, open the modal 
            btn.onclick = function() {
                modal.style.display = "block";
            }

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }

        function atualizarImagemCracha(id, image_usuario) {
            // Defina o caminho base para a imagem do usuário
            var imagePath = `{{ URL::asset('build/images/users/${id}/${image_usuario}') }}`;

            // Verifique se a imagem do usuário existe
            fetch(imagePath, {
                    method: 'HEAD'
                })
                .then((response) => {
                    if (response.ok) {
                        // Se a imagem existir, define o src como o caminho do usuário
                        document.getElementById('image_cracha').setAttribute('src', imagePath);
                    } else {
                        // Se a imagem não existir, define o src como o caminho padrão
                        document.getElementById('image_cracha').setAttribute('src',
                            `{{ URL::asset('build/images/usuarios/user-dummy-img.jpg') }}`);
                    }
                })
                .catch((error) => {
                    // Em caso de erro (como a imagem não existir), use a imagem padrão
                    document.getElementById('image_cracha').setAttribute('src',
                        `{{ URL::asset('build/images/usuarios/user-dummy-img.jpg') }}`);
                });
        }


        function formatEmailName(email) {
            // Separar a parte antes do @
            let namePart = email.split('@')[0];

            // Substituir os pontos por espaços
            namePart = namePart.replace(/\./g, ' ');

            // Converter a primeira letra de cada palavra para maiúscula
            namePart = namePart.split(' ').map(function(word) {
                return word.charAt(0).toUpperCase() + word.slice(1).toLowerCase();
            }).join(' ');

            return namePart;
        }

        
    </script>

@endsection
