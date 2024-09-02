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
                </span> Detalhes Produtos do Estoque
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

    <div class="container">
        <div class="card">
            <div class="card-body p-5">

                <div class="row">
                    <div class="col-sm-12 col-xl-6">

                        <p><strong>Obra:</strong>
                            @foreach ($obras as $obra)
                                @if ($obra->id == $produto_estoque->id_obra)
                                    {{ $obra->codigo_obra }}
                                @endif
                            @endforeach
                        </p>

                        <p><strong>Categorias: </strong>
                            @foreach ($categorias as $categorie)
                                @if ($categorie->id == $produto_estoque->id_categoria)
                                    {{ $categorie->name }}
                                @endif
                            @endforeach
                        </p>


                        <p><strong>Subcategorias: </strong>
                            @foreach ($subcategorias as $subcategorie)
                                @if ($subcategorie->id == $produto_estoque->id_subcategoria)
                                    {{ $subcategorie->name }}
                                @endif
                            @endforeach
                        </p>

                        <p><strong>Marcas: </strong>
                            @foreach ($marcas as $marca)
                                @if ($marca->id == $produto_estoque->id_marca)
                                    {{ $marca->name }}
                                @endif
                            @endforeach
                        </p>

                        <p><strong>Título: </strong>
                            {{ $produto_estoque->nome_produto }}
                        </p>

                        <p><strong>Est. minímo: </strong>
                            {{ $produto_estoque->estoque_minimo }}
                        </p>

                        <p><strong>Valor uni: </strong>
                            R$ {{ number_format($produto_estoque->valor_unitario, 2, ',', '.') }}
                        </p>

                        <p><strong>Unidade</strong>
                            {{ $produto_estoque->unidade }}
                        </p>


                        <p><strong>Situação: </strong>
                            {{ $produto_estoque->status_produto }}
                        </p>

                    </div>

                    <div class="col-sm-12  col-md-6">
                        <div class="row">
                            <div class="form-group mb-3">
                                <span class="text-danger">Extensões de imagens permitidas = 'png', 'jpg',
                                    'jpeg','gif'</span>
                            </div>

                            <div class="form-group my-3 col-6">
                                <img src="{{ @$produto_estoque->image ? asset('imagens/estoque/' . @$produto_estoque->id . '/' . @$produto_estoque->image) : URL::asset('imagens/estoque/nao-ha-fotos.png') }}"
                                    id="target" class="img-thumbnail" style="width: 450px;">
                            </div>

                            <div class="card-footer mt-4">
                                <a href="{{ route('ativo.estoque.index') }}">
                                    <button class="btn btn-danger btn-md font-weight-medium" type="button">Voltar</button>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </div>



        @endsection
