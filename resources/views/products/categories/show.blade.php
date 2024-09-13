@extends('layouts.app')

@section('content')
<div class="container">

    <div class="card">
        <div class="card-body p-5">
            <h1>Detalhes da Categoria</h1>
            <div class="row">
                <div class="col-sm-12 col-xl-8">
                    <p><strong>Nome:</strong> {{ $category->name }}</p>
                    <p><strong>Quantidade:</strong> {{ $category->color }}</p>
                    <p>
                    
                    <div id="colorDiv" style="width: 150px; height:150px; background-color: {{ $category->color ?? old('color') }}; margin: 15px"></div>

                    </p>

                </div>

            </div>

            <!-- Outros campos aqui -->
            <a href="{{ route('products.index') }}" class="btn btn-primary">Voltar</a>
        </div>
    </div>
</div>
@endsection