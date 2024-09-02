@extends('dashboard')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body p-5">
            
            <h1 class="mb-2 text-center">Produtos</h1>
            <hr class="m-0 mb-5">

            <div class="row">

                <div class="col-sm-6 col-xl-2">
                    <a href="{{ route('estoque.create') }}" class="btn btn-primary mb-3">Adicionar</a>
                </div>
                <div class="col-sm-6 col-xl-6">
                    <form method="GET" action="{{ route('estoque.index') }}">

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Pesquisar">
                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><span class="mdi mdi-magnify search-widget-icon mdi-18px p-auto"></span></button>
                        </div>                       

                    </form>
                </div>
            </div>
            

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
                            <a href="{{ route('estoque.show', $product->id) }}" class="btn btn-primary btn-sm"><i class="mdi mdi-eye"></i></a>
                            <a href="{{ route('estoque.edit', $product->id) }}" class="btn btn-secondary btn-sm mx-2"><i class="mdi mdi-pencil"></i></a>

                            <form action="{{ route('estoque.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir este produto?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="mdi mdi-delete"></i></button>
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