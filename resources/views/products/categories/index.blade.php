@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body p-5">

            <h1>Categorias</h1>

            <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Adicionar Categoria</a>

            <form method="GET" action="{{ route('categories.index') }}" class="mb-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Pesquisar">
                <button type="submit" class="btn btn-secondary">Pesquisar</button>
            </form>
            
            <table class="table">
                <thead>
                    <tr>
                        <th>Nome</th>
                        <th>Cor</th>
                        <th>Criado por</th>
                        <th>Atualizado por</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->color }}</td>
                        <td>{{ $category->created_by }}</td>
                        <td>{{ $category->updated_by }}</td>
                        <td class="d-flex">
                            <a href="{{ route('categories.show', $category->id) }}" class="btn btn-primary btn-sm mr-1"><i class="far fa-eye"></i></a>
                            <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-secondary btn-sm mr-1"><i class="fas fa-pencil-alt"></i></a>
                            <form action="{{ route('categories.destroy', $category->id) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash-alt"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $categories->links() }}
        </div>
    </div>
</div>
@endsection