@extends('dashboard')

@section('content')
<div class="container">

    <div class="card">
        <div class="card-body p-5">
            <h1>Detalhes do Produto</h1>
            
            <hr class="m-0 mb-5">
            
            <div class="row">
                <div class="col-sm-12 col-xl-8">
                    <p><strong>Nome:</strong> {{ $product->name }}</p>
                    <p><strong>Quantidade:</strong> {{ $product->quantity }}</p>
                    <p><strong>Preço Unitário:</strong>R$ {{ number_format($product->unit_price, 2, ',', '.') }}</p>
                    <p><strong>Data de Validade:</strong> {{ $product->expiry_date }}</p>
                    <p><strong>Categoria:</strong> {{ $product->category->name }}</p>
                    <p><strong>Subcategoria:</strong> {{ $product->subcategory->name }}</p>
                    <p><strong>Marca:</strong> {{ $product->brand->name }}</p>

                </div>

                <div class="col-sm-12 col-xl-4">
                    <img src="{{ $product->image }}" width="300" height="auto" title="{{ $product->name }}" alt="{{ $product->name }}">
                </div>

            </div>

            <!-- Outros campos aqui -->
            <a href="{{ route('estoque.index') }}" class="btn btn-primary">Voltar</a>
        </div>
    </div>
</div>
@endsection