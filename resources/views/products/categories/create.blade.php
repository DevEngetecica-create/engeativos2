@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body p-5">
            <h1>Cadastrar Produto</h1>
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="quantity">Cor</label>
                    <input type="text" class="form-control" id="colorInput" name="color" value="{{ old('color') }}">
                </div>

                <div id="colorDiv" style="width: 150px; height:150px; background-color: #ffff2; margin: 15px"></div>

                <button type="submit" class="btn btn-primary">Salvar</button>
            </form>
        </div>
    </div>
</div>
@endsection