@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body p-5">
            <h1>Editar Produto</h1>
            <form action="{{ route('subcategories.store') }}" method="POST">
                @csrf

                
                <div class="form-group mb-3">
                    <label for="category_id ">Categoria</label>
                    <select class="form-control select2" id="category_id " name="category_id">
                        <option value=""> Selecione uma categoria</option>
                        @foreach($categories as $category)
                        <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="quantity">Cor</label>
                    <input type="text" class="form-control" id="colorInput" name="color" value="{{ old('color') }}">
                </div>

                <div id="colorDiv" style="width: 150px; height:150px; margin: 15px"></div>

                <!-- Outros campos aqui -->
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>
    </div>
</div>
@endsection