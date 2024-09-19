@extends('dashboard')
@section('title', 'Estoque - Categorias')
@section('content')

@section('content')

    <div class="row my-3 bg-white py-3 shadow ">
        <div class="col-3 active">
            <h5 class="page-title text-left m-0">
                <a class="btn btn-success " href="{{ route('ativo.estoque.subcategorias.index') }}">
                    <i class="mdi mdi-arrow-left icon-sm align-middle text-white"></i> Voltar
                </a>
            </h5>
        </div>

        <div class="col-6 breadcrumb-item active" aria-current="page">
            <h5 class="page-title text-center">
                <span class="page-title-icon bg-gradient-primary me-2">
                    <i class="mdi mdi-access-point-network menu-icon"></i>
                </span> Editar SubCategoria do Estoque
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

                <form action="{{ route('ativo.estoque.subcategorias.update', $editSubCategoria->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="container">
                        <div class="row">
                            <div class="col">
                                <div class="form-group mb-3">
                                    <label for="category_id ">Categoria</label>
                                    <select class="form-control select2" id="category_id " name="category_id">
                                        <option value=""> Selecione uma categoria</option>
                                        @foreach ($categorias as $category)
                                            <option value="{{ $category->id }}" {{$category->id == $editSubCategoria->category_id ? 'selected' : ''}}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group mb-3">
                                    <label for="name">Nome</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ $editSubCategoria->name ?? old('name') }}">
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-group mb-3">
                                    <label for="quantity">Cor</label>
                                    <input type="text" class="form-control" id="colorInput" name="color"
                                        value="{{ $editSubCategoria->color ?? old('color') }}">
                                </div>
                            </div>
                        </div>

                        <div id="colorDiv"
                            style="width: 150px; height:150px; background-color: {{ $editSubCategoria->color ?? old('color') }}; margin: 15px">
                        </div>

                        <button type="submit" class="btn btn-primary">Salvar</button>
                </form>
            </div>
        </div>
    </div>
@endsection
