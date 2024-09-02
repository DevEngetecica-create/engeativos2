@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body p-5 pt-3">
            <h1>Editar Subcategorias</h1>
            <form action="{{ route('subcategories.update', $subcategory->id) }}" method="POST">
                @csrf
                @method('PUT') <!-- Certifique-se de incluir esta diretiva -->

                <div class="form-group mb-3">
                    <label for="category_id ">Categoria</label>
                    <select class="form-select select2" id="category_id" name="category_id">
                        <option value=""> Selecione uma categoria</option>
                        @foreach($categories as $category)
                        <option value="{{$category->id}}" {{$category->id == $subcategory->category_id ? 'selected' : ""}}>{{$category->name}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $subcategory->name ?? old('name') }}">
                </div>
                <div class="form-group">
                    <label for="quantity">Cor</label>
                    <input type="text" class="form-control" id="colorInput" name="color" value="{{$subcategory->color ?? old('color') }}">
                </div>

                <div id="colorDiv" style="width: 150px; height:150px; margin: 15px; background-color: {{$subcategory->color}}"></div>

                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>
    </div>
</div>
@endsection