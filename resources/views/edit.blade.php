@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body p-5">
            <h1>Editar Produto</h1>
            <div class="row">
                <div class="col-sm-12 col-xl-8">
                    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label for="name">Nome</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $product->name }}">
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantidade</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" value="{{ $product->quantity }}">
                        </div>

                        <div class="form-group">
                            <label for="minimum_stock">Und. de medida</label>
                            <input type="text" class="form-control" id="unit" name="unit" value="{{ $product->unit ?? old('unit') }}">
                        </div>
                        <div class="form-group">
                            <label for="minimum_stock">Estoque min</label>
                            <input type="text" class="form-control" id="minimum_stock" name="minimum_stock" value="{{ $product->minimum_stock ?? old('minimum_stock') }}">
                        </div>

                        <div class="form-group">
                            <label for="unit_price">Preço Unitário</label>
                            <input type="text" class="form-control" id="unit_price" name="unit_price" value="{{ number_format($product->unit_price, 2, ',', '.') }}">
                        </div>
                        <div class="form-group">
                            <label for="expiry_date">Data de Validade</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" value="{{ $product->expiry_date }}">
                        </div>
                        <div class="form-group">
                            <label for="category_id">Categoria</label>
                            <select class="form-control" id="category_id" name="category_id">
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ $category->id == $product->category_id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subcategory_id">Subcategoria</label>
                            <select class="form-control" id="subcategory_id" name="subcategory_id">
                                @foreach($subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" {{ $subcategory->id == $product->subcategory_id ? 'selected' : '' }}>{{ $subcategory->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="brand_id">Marca</label>
                            <select class="form-control" id="brand_id" name="brand_id">
                                @foreach($brands as $brand)
                                <option value="{{ $brand->id }}" {{ $brand->id == $product->brand_id ? 'selected' : '' }}>{{ $brand->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="image">Imagem</label>
                            <input type="file" class="form-control" name="image" id="image" onchange="previewImage(event)">
                            @if($product->image)
                            <img id="preview" src="{{ asset("build/assets/images/product/{$product->id}/" . $product->image) }}" class="border mt-2" width="300">
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary">Salvar</button>
                    </form>
                </div>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $('#unit_price').mask('000.000.000.000.000,00', {
                    reverse: true
                });
            });

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