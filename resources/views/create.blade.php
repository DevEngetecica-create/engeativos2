@extends('layouts.app')

@section('content')

<div class="container">
    <h1>Cadastrar Produto</h1>

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

    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Nome</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
        </div>
        <div class="form-group">
            <label for="quantity">Quantidade</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ old('quantity') }}">
        </div>
        <div class="form-group">
            <label for="minimum_stock">Und. de medida</label>
            <input type="text" class="form-control" id="unit" name="unit" value="{{ old('unit') }}">
        </div>
        <div class="form-group">
            <label for="minimum_stock">Estoque min</label>
            <input type="text" class="form-control" id="minimum_stock" name="minimum_stock" value="{{ old('minimum_stock') }}">
        </div>
        <div class="form-group">
            <label for="unit_price">Preço Unitário</label>
            <input type="text" class="form-control" id="unit_price" name="unit_price" value="{{ old('unit_price') }}">
        </div>
        <div class="form-group">
            <label for="expiry_date">Data de Validade</label>
            <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ old('expiry_date') }}">
        </div>
        <div class="form-group">
            <label for="category_id">Categoria</label>
            <select class="form-control" id="category_id" name="category_id">
                @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="subcategory_id">Subcategoria</label>
            <select class="form-control" id="subcategory_id" name="subcategory_id">
                @foreach($subcategories as $subcategory)
                <option value="{{ $subcategory->id }}">{{ $subcategory->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="brand_id">Marca</label>
            <select class="form-control" id="brand_id" name="brand_id">
                @foreach($brands as $brand)
                <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="image">Imagem</label>
            <input type="file" class="form-control" name="image" id="image" onchange="previewImage(event)">
        </div>
        <div class="col-sm-12 col-xl-4">
            <img src="" id="preview" class="border" alt="Pré-visualização da imagem" width="300">
        </div>
        <button type="submit" class="btn btn-primary">Salvar</button>
    </form>
</div>

<script>
    function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function() {
            var output = document.getElementById('preview');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection