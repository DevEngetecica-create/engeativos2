@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body p-5">
            <h1>Editar Produto</h1>
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ $category->name ?? old('name') }}">
                </div>
                <div class="form-group">
                    <label for="quantity">Cor</label>
                    <input type="text" class="form-control" id="colorInput" name="color" value="{{ $category->color ?? old('color') }}">
                </div> 

                <div id="colorDiv" style="width: 150px; height:150px; background-color: {{ $category->color ?? old('color') }}; margin: 15px"></div>

                <!-- Outros campos aqui -->
                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>
    </div>
</div>
@endsection