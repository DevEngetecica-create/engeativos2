@extends('dashboard')
@section('title', 'Produtos do Estoque')
@section('content')



    <div class="row my-3 bg-white py-3 shadow ">
        <div class="col-3 active">
            <h5 class="page-title text-left m-0">
                <a class="btn btn-success btn-sm" href="{{ route('ativo.estoque.index') }}">
                    <i class="mdi mdi-arrow-left icon-sm align-middle text-white"></i> Voltar
                </a>
            </h5>
        </div>

        <div class="col-6 breadcrumb-item active" aria-current="page">
            <h5 class="page-title text-center">
                <span class="page-title-icon bg-gradient-primary me-2">
                    <i class="mdi mdi-access-point-network menu-icon"></i>
                </span> Editar Produtos do Estoque
            </h5>
        </div>
    </div>



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


    <form method="post" action="{{ route('ativo.estoque.update', $produto_estoque->id) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12 col-md-5">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Dados do Produto</h3>
                            </div>

                            <div class="card-body">

                                <div class="form-group mb-2">
                                    <label for="obra_id">Obra </label>
                                    <select class="form-select select2" id="id_obra" name="id_obra">
                                        <option value="">Selecione uma obra</option>

                                        @foreach ($obras as $obra)
                                            <option value="{{ $obra->id }}"
                                                {{ $obra->id == $produto_estoque->id_obra ? 'selected' : '' }}>
                                                {{ $obra->codigo_obra }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group mb-2">
                                    <label for="obra_id">Categorias </label>
                                    <select class="form-select select2" id="id_categoria" name="id_categoria">
                                        <option value="">Selecione uma Categoria</option>
                                        @foreach ($categorias as $categorie)
                                            <option value="{{ $categorie->id }}"
                                                {{ $categorie->id == $produto_estoque->id_categoria ? 'selected' : '' }}>
                                                {{ $categorie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group mb-2">
                                    <label for="obra_id">Subcategorias </label>
                                    <select class="form-select select2" id="id_subcategoria" name="id_subcategoria">
                                        <option value="">Selecione uma Subcategoria</option>
                                        @foreach ($subcategorias as $subcategorie)
                                            <option value="{{ $subcategorie->id }}"
                                                {{ $subcategorie->id == @$produto_estoque->id_subcategoria ? 'selected' : '' }}>
                                                {{ $subcategorie->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="marca">Marcas </label>
                                    <select class="form-select select2 " id="id_marca" name="id_marca" required>
                                        <option value="">Selecione uma marca</option>

                                        @foreach ($marcas as $brad)
                                            <option value="{{ $brad->id }}"
                                                {{ $brad->id == $produto_estoque->id_marca ? 'selected' : '' }}>
                                                {{ $brad->name }}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <div class="form-group mb-2">
                                    <label class="form-label" for="nome_produto">Título:</label>
                                    <input class="form-control " id="nome_produto" name="nome_produto"
                                        value="{{ $produto_estoque->nome_produto ?? old('nome_produto') }}" type="text"
                                        placeholder="Título" required>
                                </div>

                                <div class="row">

                                    <div class="form-group mb-2 col-sm-6  col-xl-6">
                                        <label class="form-label" for="estoque_minimo">Est. minímo</label>
                                        <input class="form-control " id="estoque_minimo" name="estoque_minimo"
                                            value="{{ $produto_estoque->estoque_minimo ?? old('estoque_minimo') }}"
                                            type="text" required>
                                    </div>

                                    <div class="form-group mb-2 col-sm-6  col-xl-6">
                                        <label class="form-label" for="estoque_minimo">Valor un</label>
                                        <input class="form-control " id="valor_unitario" name="valor_unitario"
                                            value="{{ $produto_estoque->valor_unitario ?? old('valor_unitario') }}"
                                            type="text" required>
                                    </div>

                                    <div class="form-group mb-2 col-sm-6 col-lg-6">
                                        <label class="form-label" for="unidade">Unidade</label>
                                        <input class="form-control " id="unidade" name="unidade"
                                            value="{{ $produto_estoque->unidade ?? old('unidade') }}" type="text"
                                            required>
                                    </div>
                                </div>

                                <div class="row mb-2">
                                    <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                        <label class="form-label" for="status_produto">Situação: </label>
                                        <select class="form-control form-select" id="status_produto"
                                            value="{{ $produto_estoque->status_produto ?? old('status_produto') }}"
                                            name="status_produto" required>
                                            <option value="1">Ativo</option>
                                            <option value="0">Inativo</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12  col-md-6">
                        <div class="card mb-2">
                            <div class="card-header">
                                <h3 class="card-title">Imagem do Produto</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group mb-3">
                                        <label for="formFile" class="form-label">
                                            <h5> Imagem (300 x 300)</h5>
                                        </label>
                                        <input class="form-control" type="file" name="image" id="image"
                                            onChange="carregarImg()">
                                        <span class="text-danger">Extensões de imagens permitidas = 'png', 'jpg', 'jpeg',
                                            'gif'</span>

                                    </div>
                                    <div class="form-group my-3 col-6">



                                        <img src="{{ @$produto_estoque->image ? asset('imagens/estoque/' . @$produto_estoque->id . '/' . @$produto_estoque->image) : URL::asset('imagens/estoque/nao-ha-fotos.png') }}"
                                            id="target" class="img-thumbnail" style="width: 450px;">

                                        

                                    </div>
                                    <div class="card-footer mt-4">
                                        <button class="btn btn-primary btn-md font-weight-medium"
                                            type="submit">Salvar</button>

                                        <a href="{{ route('ativo.estoque.index') }}">
                                            <button class="btn btn-danger btn-md font-weight-medium"
                                                type="button">Cancelar</button>
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>



        </div>
        </div>
    </form>

@endsection

@section('script')
    <!--SCRIPT PARA CARREGAR IMAGEM PRINCIPAL -->
    <script type="text/javascript">
        function carregarImg() {

            var target = document.getElementById('target');
            var file = document.querySelector("input[type=file]").files[0];
            var reader = new FileReader();

            reader.onloadend = function() {
                target.src = reader.result;
            };

            if (file) {
                reader.readAsDataURL(file);


            } else {
                target.src = "";
            }
        }
    </script>

@endsection
