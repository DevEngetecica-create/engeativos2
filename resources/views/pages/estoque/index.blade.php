@extends('dashboard')
@section('title', 'Ativos Internos')
@section('content')


<div class="row">
    <div class="col-2 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-center">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-office-building-cog mdi-24px"></i>
            </span> Produtos
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Estoque <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>

</div>

<hr>

<form action="{{ route('ativo.estoque.index') }}" method="GET" class="mb-4">
    @csrf
    <div class="row my-4">
        <div class="col-2">
            <h3 class="page-title text-left">

                @if (session()->get('usuario_vinculo')->id_nivel <= 2) 
                    <a href="{{ route('ativo.estoque.create') }}">
                        <span class="btn btn-success">Novo Registro</span>
                    </a>
                @endif

            </h3>
        </div>

        <div class="col-10">
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <input type="text" class="form-control shadow" name="produto" placeholder="Pesquisar produto" value="{{ request()->produto }}">
                </div>
                <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                    <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i class="mdi mdi-file-search-outline mdi-24px"></i></button>

                    <a href="{{ route('ativo.estoque.index') }}" title="Limpar pesquisa">
                        <span class="btn btn-warning btn-sm py-0 shadow"><i class="mdi mdi-delete-outline mdi-24px"></i></span>
                    </a>
                </div>
                <div class="col-1 text-left m-0">

                </div>
            </div>
        </div>
    </div>
</form>


<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-hover align-middle table-nowrap mb-0">
            <thead>
                <tr>
                    <th class="text-center" width="8%">ID</th>
                    <th>Obra</th>
                    <th>Categoria</th>
                    <th>Nome</th>
                    <th class="text-center">Valor unit.</th>
                    <th class="text-center">Valor em estoque</th>
                    <th class="text-center">Estoque Min</th>

                    <th class="text-center">Qtde em estoque</th>
                   

                    @if (session()->get('usuario_vinculo')->id_nivel <= 2) <th class="text-center" width="10%">Ações</th>
                        @endif

                </tr>
            </thead>
            <tbody>
                @foreach ($produtos as $produto)
                <tr>
                    <td class="text-center align-middle">{{ $produto->id }}</td>
                    <td class="align-middle">{{ $produto->obra->razao_social ?? "sem reg."}}</td>
                    <td class="align-middle">{{ $produto->categoria->titulo ?? "sem reg."}}</td>
                    <td class="align-middle">{{ $produto->nome_produto ?? "sem reg."}}</td>
                    <td class="text-center">R$ {{ number_format($produto->valor_unitario ?? 0, 2, ',', '.') }}</td>
                    <td class="text-center">R$ {{ number_format($produto->valor_total ?? 0, 2, ',', '.') }}</td>
                    <td class="align-middle text-center">{{ $produto->estoque_minimo }}</td>
                    <td class="align-middle text-center">{{ $produto->quantidade }}</td>

                    <!-- Default Modals -->
                    <!--  @if (session()->get('usuario_vinculo')->id_nivel <= 2) -->
                    <td class="d-flex gap-2 align-middle">
                        <a class="m-0" data-bs-toggle="modal" id="anexos_estoque" data-bs-target="#myModal" href="javascript:void(0)" data-id="{{ $produto->id}}">
                            <button class="btn btn-success btn-sm" title="Arquivos">
                                <i class="mdi mdi-file"></i>
                            </button>
                        </a>

                        <a class="m-0" href="{{ route('ativo.estoque.edit', $produto->id) }}" title="Editar">
                            <button class="btn btn-warning btn-sm">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                        </a>

                        <a class="m-0" href="{{ route('ativo.estoque.show', $produto->id) }}" title="Visualizar">
                            <button class="btn btn-info btn-sm">
                                <i class="mdi mdi-eye"></i>
                            </button>
                        </a>

                        <form class="m-0" action="{{ route('ativo.estoque.destroy', $produto->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </form>
                    </td>
                    <!-- @endif -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="myModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="anexarArquivoAtivoVeiculoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-bottom">
                <h5 class="modal-title" id="anexarArquivoAtivoExternoLabel">Anexo do Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body" id="lista_anexo">

            </div>

            <div id="mensagem"></div>

            <form id="cad_anexo_estoque" method="post" enctype="multipart/form-data">
                @csrf

                <div class="modal-body">

                    <div class="row">
                        <div class="mb-3 col-6">
                            <label class="form-label">Nome do arquivo</label>
                            <input type="text" class="form-control" id="nome_arquivo" name="nome_arquivo" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <input type="file" accept="image/*,.pdf" class="form-control" id="file" name="file" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <input type="hidden" id="id_produto">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" id="cadastrar_anexos_estoque" class="btn btn-warning">Salvar Anexo</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    $(document).ready(function() {
        $(document).on("click", "#anexos_estoque", function() {

            var id_produto = $(this).data('id');

            // Extrai o valor de data-id
            var idProduto = $(this).attr('data-id');

            // Define esse valor no input #id_produto
            $('#id_produto').val(idProduto);

            var url_anexo_produto = "{{route('ativo.estoque.anexos', ['id' => ':id'])}}";
            url_anexo_produto = url_anexo_produto.replace(':id', id_produto);

            $(".modal-body #id_item").val(id_produto);

            $.ajax({
                    url: url_anexo_produto,
                    type: 'get',
                    data: {}
                })
                .done(function(result) {
                    $("#lista_anexo").html(result)
                })
                .fail(function(jqXHR, textStatus, result) {

                });
        });

        // Função para carregar os dados da tabela
        function carregarDadosTabela() {
            $.ajax({
                url: url_anexo_produto, // URL para a rota que retorna os dados da tabela
                type: 'GET',
                dataType: 'json', // Espera-se que o servidor retorne JSON
                success: function(data) {
                    var linhas = '';
                    $.each(data, function(index, elemento) {
                        linhas += '<tr>' +
                            '<td>' + elemento.created_at + '</td>' +
                            '<td>' + elemento.nome_arquivo + '</td>' +
                            // Adicione outras colunas conforme necessário
                            '</tr>';
                    });
                    $('#idDaTabela > tbody').html(linhas); // Atualiza o corpo da tabela com as novas linhas
                }
            });
        }
        // Função para formatar a data
        function formatarData(dataString) {
            var dataObj = new Date(dataString);
            var dia = ("0" + dataObj.getDate()).slice(-2); // Adiciona zero à esquerda se necessário e pega os últimos 2 dígitos
            var mes = ("0" + (dataObj.getMonth() + 1)).slice(-2); // Meses começam do 0
            var ano = dataObj.getFullYear();
            return dia + "/" + mes + "/" + ano;
        }


        $(document).on("click", "#cadastrar_anexos_estoque", function() {
            var id_cadastrar = $('#id_produto').val(); // Melhor usar .data() para dados data-*
            var form = $('#cad_anexo_estoque')[0]; // Pega o formulário como um elemento DOM
            var formData = new FormData(form);
            formData.append('id', id_cadastrar); // Adiciona o ID ao FormData

            var url_anexo_produto = "{{route('ativo.estoque.anexos', ['id' => ':id'])}}";
            url_anexo_produto = url_anexo_produto.replace(':id', id_cadastrar);


            $.ajax({
                type: 'POST',
                url: '/admin/ativo/estoque/fileUpload',
                data: formData,
                processData: false, // Impede o jQuery de processar os dados
                contentType: false, // Impede o jQuery de definir o tipo de conteúdo
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            }).done(function(data) {


                var listar = "listar";

                $.ajax({
                    url: url_anexo_produto, // URL para a rota que retorna os dados da tabela
                    type: 'GET',
                    data: {
                        listar: listar
                    },
                    dataType: 'json', // Espera-se que o servidor retorne JSON
                    success: function(response) {

                        // Uso da função para formatar a data
                        var dataFormatada = formatarData(response.created_at);


                        var linhas = '';
                        var linha = '<tr>' +
                            '<td class="text-center">' + response.id + '</td>' +
                            '<td class="text-center">' + dataFormatada + '</td>' +
                            '<td class="text-center">' + response.nome_arquivo + '</td>' +
                            '<td class="text-center">' +

                            '<a href="/admin/ativo/estoque/anexos/' + response.id + '">' +

                            '<span class="btn btn-success btn-sm" title="Baixar anexo"><i class="mdi mdi-download"></i></span>' +
                            '</a>' +

                            '<a  id="deletar_anexos_estoque"  data-id="' + response.id + '">' +
                            '<span class="btn btn-danger btn-sm mx-1" title="Excluir"><i class="mdi mdi-delete"></i></span>' +
                            '</a>' +
                            '</td>' +
                            // Adicione outras colunas conforme necessário
                            '</tr>';
                        $('#idDaTabela > tbody').append(linha); // Usa append() em vez de html() para adicionar ao existente

                    }
                });

                // Primeiro, limpa o conteúdo anterior, insere o novo conteúdo e esconde para preparação do fadeIn
                $('#mensagem').html('').html('<span class="fs-6 badge text-bg-success mx-2">' + data + ' </span>').hide();

                // Faz a mensagem aparecer suavemente
                $('#mensagem').fadeIn(2000);

                // Aguarda 2 segundos (2000 milissegundos), então faz a mensagem desaparecer suavemente
                setTimeout(function() {
                    $('#mensagem').fadeOut(500);
                }, 2000);
            });
        });


        //deletar arquivo



        $(document).on("click", "#deletar_anexos_estoque", function() {
            $('#mensagem').html('');

            var id_produto = $(this).data('id');
            var url_deletar_anexo_produto = "{{route('ativo.estoque.store.destroy_file')}}"; // Assegure-se que este template literal está sendo processado em um arquivo .blade.php
            var rowToDelete = '#produto-' + id_produto; // Constrói o seletor da linha a ser deletada

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            console.log(url_deletar_anexo_produto);

            $.ajax({
                type: 'POST',
                url: url_deletar_anexo_produto,
                data: {
                    id_produto: id_produto,
                    _method: 'POST'
                }
            }).done(function(data) {
                // Exibe a mensagem de sucesso
                $('#mensagem').html('<span class="fs-6 badge text-bg-success mx-2">' + data + ' </span>').hide().fadeIn(2000);

                // Espera a mensagem desaparecer antes de remover a linha
                setTimeout(function() {
                    $('#mensagem').fadeOut(2000, function() {
                        // Após a mensagem desaparecer, começa o fade out da linha
                        $(rowToDelete).fadeOut(2000, function() {
                            // Remove a linha após o fade out
                            $(this).remove();
                        });
                    });
                }, 2000);
            });
        });


    });
</script>
@endsection