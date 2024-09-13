@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body p-5">

            <h1>Produtos</h1>
            <a href="{{ route('products.create') }}" class="btn btn-success my-3">Adicionar Produto</a>

            <form method="GET" action="{{ route('products.index') }}">
                <input type="text" class="forn-control" name="search" value="{{ request('search') }}" placeholder="Pesquisar">
                <button type="submit" class="btn btn-primary">Pesquisar</button>
            </form>

            <table class="table table-sm mt-5">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Data de Validade</th>
                        <th>Categoria</th>
                        <th>Subcategoria</th>
                        <th>Marca</th>
                        <th>Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td>
                        {{ $product->id }}
                        </td>
                        <td>{{ $product->name }}</td>

                        <td>R$ {{ number_format($product->unit_price, 2, ',', '.') }}</td>
                        <td>{{ $product->expiry_date }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ $product->subcategory->name }}</td>
                        <td>{{ $product->brand->name }}</td>
                        <td class="d-flex text-center">
                            <a href="{{ route('products.show', $product->id) }}" class="btn btn-primary btn-sm"><i class="far fa-eye"></i></a>
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-secondary btn-sm mx-2"><i class="fas fa-pencil-alt"></i></a>

                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection