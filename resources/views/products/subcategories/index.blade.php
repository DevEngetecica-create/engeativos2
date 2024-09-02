@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body p-5 pt-3">

            <h1 class="mb-2 text-center">Subcategoria</h1>
            <hr class="m-0 mb-5">

            <div class="row">

                <div class="col-sm-6 col-xl-2">
                    <a href="{{ route('subcategories.create') }}" class="btn btn-primary mb-3">Adicionar</a>
                </div>
                <div class="col-sm-6 col-xl-6">
                    <form method="GET" action="{{ route('subcategories.index') }}">

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Pesquisar">
                            <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><i class="fas fa-search"></i></button>
                        </div>                       

                    </form>
                </div>
            </div>
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th class="text-center" width="10%">ID</th>
                        <th>Categortia</th>
                        <th>Subcategoria</th>
                        <th>Qtde produtos</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($subcategories as $subcategory)
                    <tr>

                        <td class="text-center">
                            {{ $subcategory->id }}
                        </td>
                        <td>
                            {{ $subcategory->category->name }}
                        </td>

                        <td>{{ $subcategory->name }}</td>
                        <td>{{ $subcategory->name }}</td>


                        <td class="d-flex justify-content-center">
                            <a href="{{ route('subcategories.show', $subcategory->id) }}" class="btn btn-primary btn-sm"><i class="far fa-eye"></i></a>
                            <a href="{{ route('subcategories.edit', $subcategory->id) }}" class="btn btn-secondary btn-sm mx-2"><i class="fas fa-pencil-alt"></i></a>
                            <form action="{{ route('subcategories.destroy', $subcategory->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $subcategories->links() }}
        </div>
    </div>
</div>
@endsection