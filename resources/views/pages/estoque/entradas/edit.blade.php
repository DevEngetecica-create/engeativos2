@extends('dashboard')
@section('title', 'Editar Entrada de Produto')
@section('content')

    <div class="card">
        <div class="card-body">
            <div class="row justify-content-center col-sm-12 col-lg-4 col-xl-12">
                <div class="col-5">
                    <h3 class="page-title">
                        <span class="page-title-icon bg-gradient-primary me-2">
                            <i class="mdi mdi-dolly"></i>
                        </span> Editar Entrada de Produtos<i class="mdi mdi-dolly mdi-36px mx-4 align-middle"></i>
                    </h3>
                </div>
            </div>

            <hr class="m-0 mb-2">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>Ops!</strong><br><br>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="post" action="{{ route('ativo.estoque.entrada.update', $produto_entrada->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-3">
                        <div class="form-group mb-2">
                            <label for="obra_id">Obra </label>
                            <select class="form-select select2" id="id_obra" name="id_obra">
                                @if (session()->get('obra')['id'] == null)
                                    <option value="">Selecione uma obra</option>
                                    @foreach ($obras as $obra)
                                        <option value="{{ $obra->id }}" {{ $obra->id == $produto_entrada->id_obra ? 'selected' : '' }}>
                                            {{ $obra->codigo_obra }}
                                        </option>
                                    @endforeach
                                @else
                                    @foreach ($obras as $obra)
                                        <option value="{{ $obra->id }}" {{ $obra->id == $produto_entrada->id_obra ? 'selected' : '' }}>
                                            {{ $obra->codigo_obra }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group mb-2">
                            <label for="obra_id">Categorias </label>
                            <select class="form-select select2" id="id_categoria" name="id_categoria">
                                <option value="">Selecione uma Categoria</option>
                                @foreach ($categorias as $categorie)
                                    <option value="{{ $categorie->id }}" {{ $categorie->id == $produto_entrada->id_categoria ? 'selected' : '' }}>
                                        {{ $categorie->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group mb-2">
                            <label for="obra_id">Subcategorias </label>
                            <select class="form-select select2" id="id_subcategoria" name="id_subcategoria">
                                <option value="">Selecione uma Subcategoria</option>
                                @foreach ($subcategorias as $subcategorie)
                                    <option value="{{ $subcategorie->id }}" {{ $subcategorie->id == $produto_entrada->id_subcategoria ? 'selected' : '' }}>
                                        {{ $subcategorie->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group mb-3">
                            <label for="obra_id">Produto </label>
                            <input class="form-control form-control-sm " id="nome_produto" name="nome_produto"
                                value="{{ $produto_entrada->nome_produto }}" placeholder="Nome do Produto">
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-1">
                        <div class="form-group mb-3">
                            <label for="quantidade_entrada">Qtde. </label>
                            <input class="form-control form-control-sm text-center"
                                value="{{ $produto_entrada->quantidade_entrada }}" name="quantidade_entrada" type="text"
                                required>
                        </div>
                    </div>

                    <div class="col-1">
                        <div class="form-group mb-3">
                            <label for="valor_unitario_entrada">Valor unit.</label>
                            <input class="form-control form-control-sm text-center"
                                value="{{ number_format($produto_entrada->valor_unitario_entrada, 2, ',', '.') }}"
                                name="valor_unitario_entrada" id="valor_unitario_entrada" type="text" required>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group mb-3">
                            <label for="valor_total_entrada">Valor total</label>
                            <input class="form-control form-control-sm text-center valor_total"
                                value="{{ number_format($produto_entrada->valor_total_entrada, 2, ',', '.') }}"
                                name="valor_total_entrada" id="valor_total_entrada" type="text" required>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="form-group mb-3">
                            <label for="nota_fical">Nº NF</label>
                            <input class="form-control form-control-sm" value="{{ $produto_entrada->num_nf }}"
                                name="num_nf" type="text" required>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group mb-3">
                            <label for="arquivo_nf">Arquivo</label>
                            <input class="form-control form-control-sm" type="file" id="arquivo_nf" name="arquivo_nf">
                        </div>
                    </div>
                </div>

                <div class="row my-3 bg-success-subtle pt-2 d-none" id="epis">
                    <div class="col-sm-6 col-xxl-1">
                        <div class="form-group mb-3">
                            <label for="cert_aut_entrada">CA</label>
                            <input class="form-control form-control-sm" value="{{ $produto_entrada->cert_aut_entrada }}"
                                id="cert_aut_entrada" name="cert_aut_entrada" placeholder="CA">
                        </div>
                    </div>

                    <div class="col-sm-6 col-xxl-1">
                        <div class="form-group mb-3">
                            <label for="num_lote_entrada">Nº do lote</label>
                            <input class="form-control form-control-sm" value="{{ $produto_entrada->num_lote_entrada }}"
                                id="num_lote_entrada" name="num_lote_entrada" placeholder="Nº Lote">
                        </div>
                    </div>

                    <div class="col-sm-6 col-xxl-2">
                        <div class="form-group mb-3">
                            <label for="data_validade_lote_ca">Data de validade do lote</label>
                            <input class="form-control form-control-sm"
                                value="{{ $produto_entrada->data_validade_lote_ca }}" type="date"
                                id="data_validade_lote_ca" name="data_validade_lote_ca"
                                title="data de validade do lote">
                        </div>
                    </div>

                    <div class="col-sm-6 col-xxl-4">
                        <div class="form-group mb-3">
                            <label for="arquivo_ca">Certficado CA</label>
                            <input class="form-control form-control-sm" value="{{ $produto_entrada->arquivo_ca }}"
                                type="file" id="arquivo_ca" name="arquivo_ca">
                        </div>
                    </div>
                </div>

                <div class="card-footer mt-2">
                    <button type="submit" class="btn btn-success">Salvar</button>
                    <a href="{{ route('ativo.estoque.index') }}">
                        <span class="btn btn-warning">Cancelar</span>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {

            function toggleEpisDisplay() {
                if ($("#id_categoria").val() == 1) {
                    $('#epis').removeClass('d-none').addClass('d-flex');
                } else {
                    $('#epis').removeClass('d-flex').addClass('d-none');
                }
            }

            // Verifica o valor inicial ao carregar a página
            toggleEpisDisplay();

            // Adiciona o evento de mudança para atualizar a exibição ao alterar a categoria
            $("#id_categoria").change(function() {
                toggleEpisDisplay();
            });
        });
    </script>

@endsection
