@extends('dashboard')
@section('title', 'Ativos Internos')
@section('content')

    <div class="row">
        <div class="col-2 breadcrumb-item active" aria-current="page">
            <h3 class="page-title text-center">
                <span class="page-title-icon bg-gradient-primary me-2">
                    <i class="mdi mdi-office-building-cog mdi-24px"></i>
                </span> Estoque
            </h3>
        </div>

        <div class="col-4 active m-2">
            <h5 class="page-title text-left m-0">
                <span>Marcas <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
            </h5>
        </div>

    </div>

    <hr>

    <form action="{{ route('ativo.estoque.marcas.index') }}" method="GET" class="mb-4">
        @csrf
        <div class="row my-4">
            <div class="col-2">
                <h3 class="page-title text-left">
                    @if (session()->get('usuario_vinculo')->id_nivel <= 2)
                        <a href="{{ route('ativo.estoque.marcas.create') }}">
                            <span class="btn btn-success">Novo Registro</span>
                        </a>
                    @endif
                </h3>
            </div>

            <div class="col-10">
                <div class="row justify-content-center">
                    <div class="col-5 m-0 p-0 ">
                        <input type="text" class="form-control shadow" name="search" placeholder="Pesquisar marca"
                            value="{{ request()->search }}">
                    </div>
                    <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                        <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i
                                class="mdi mdi-file-search-outline mdi-24px"></i></button>

                        <a href="{{ route('ativo.estoque.marcas.index') }}" title="Limpar pesquisa">
                            <span class="btn btn-warning btn-sm py-0 shadow"><i
                                    class="mdi mdi-delete-outline mdi-24px"></i></span>
                        </a>
                    </div>
                    <div class="col-1 text-left m-0">

                    </div>
                </div>
            </div>
        </div>
    </form>

    <div class="card">
        <div class="card-body p-5 pt-3">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th class="text-center" width="10%">ID</th>
                        <th>Nome</th>
                        <th class="text-center">Ações</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($marcas as $brand)
                        <tr>
                            <td class="text-center">
                                {{ $brand->id }}
                            </td>
                            <td>
                                {{ $brand->name }}
                            </td>
                            <td class="d-flex justify-content-center">
                                {{--  <a href="{{ route('ativo.estoque.marcas.show', $brand->id) }}"
                                    class="btn btn-primary btn-sm"><i class="mdi mdi-eye"></i></a> --}}

                                <a href="{{ route('ativo.estoque.marcas.edit', $brand->id) }}"
                                    class="btn btn-secondary btn-sm mx-2"><i class="mdi mdi-pencil"></i></a>

                                <form action="{{ route('ativo.estoque.marcas.destroy', $brand->id) }}" method="POST"
                                    onsubmit="return confirm('Tem certeza que deseja excluir esta categoria?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"><i
                                            class="mdi mdi-delete"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $marcas->links() }}
        </div>
    </div>



@endsection
