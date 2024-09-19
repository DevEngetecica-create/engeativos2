@extends('dashboard')
@section('title', 'Ativos Internos')
@section('content')

    <div class="row">
        <div class="col-2 breadcrumb-item active" aria-current="page">
            <h3 class="page-title text-center">
                <span class="page-title-icon bg-gradient-primary me-2">
                    <i class="mdi mdi-office-building-cog mdi-24px"></i>
                </span> Entrada - Estoque
            </h3>
        </div>
        <div class="col-4 active m-2">
            <h5 class="page-title text-left m-0">
                <span>Estoque <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
            </h5>
        </div>
    </div>

    <hr>

    <form action="{{ route('ativo.estoque.entrada.index') }}" method="GET" class="mb-4">

        @csrf

        <div class="row my-4">
            <div class="col-2">
                <h3 class="page-title text-left">
                    @if (session()->get('usuario_vinculo')->id_nivel <= 2)
                        <a href="{{ route('ativo.estoque.entrada.create') }}">
                            <span class="btn btn-sm btn-success">Novo Registro</span>
                        </a>
                    @endif
                </h3>
            </div>

            <div class="col-10">
                <div class="row justify-content-center">
                    <div class="col-5 m-0 p-0 ">
                        <input type="text" class="form-control shadow" name="produto" placeholder="Pesquisar produto"
                            value="{{ request()->produto }}">

                    </div>

                    <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">
                        <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i
                                class="mdi mdi-file-search-outline mdi-24px"></i></button>
                        <a href="{{ route('ativo.estoque.saida.index') }}" title="Limpar pesquisa">
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
        <div class="card-body">
            <table class="table table-bordered table-hover align-middle table-nowrap mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="8%">ID</th>
                        <th>Obra</th>
                        <th>Categoria</th>
                        <th>Item</th>
                        <th class="text-center">Qtde </th>
                        <th class="text-center">Valor unit.</th>
                        <th class="text-center">Valor total</th>
                        <th class="text-center">Nota Fical</th>
                        <th class="text-center">Data de cadastro.</th>
                        <th class="text-center" width="10%">Ações</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach ($produtos_entradas as $produtos_entrada)

                    {{--dd($produtos_entrada)--}}
                        <tr>
                            <td class="text-center align-middle">{{ $produtos_entrada->id }}</td>
                            <td class="align-middle">{{ $produtos_entrada->obra->nome_fantasia ?? "Sem reg." }}</td>
                            <td class="align-middle">{{ $produtos_entrada->produto->categorias->name ?? "Sem reg." }}</td>
                            <td class="align-middle">{{ $produtos_entrada->produto->nome_produto ?? "Sem reg."}}</td>
                            <td class="align-middle text-center">{{ $produtos_entrada->quantidade_entrada }}</td>
                            <td class="text-center">R$
                                {{ number_format($produtos_entrada->valor_unitario_entrada, 2, ',', '.') }}</td>
                            <td class="text-center ">R$
                                {{ number_format($produtos_entrada->valor_total_entrada, 2, ',', '.') }}</td>
                            <td class="text-center ">{{ $produtos_entrada->num_nf }}</td>
                            <td class="text-center" class="align-middle">
                                {{ Tratamento::datetimeBr($produtos_entrada->created_at) }}</td>

                            <!--  @if (session()->get('usuario_vinculo')->id_nivel <= 2)
    -->

                            <td class="d-flex  justify-content-center gap-2 align-middle">
                                <a class="m-0" href="{{ route('ativo.estoque.entrada.edit', $produtos_entrada->id) }}">
                                    <button class="btn btn-warning btn-sm">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                </a>

                                <a class="m-0" href="{{ route('ativo.estoque.entrada.show', $produtos_entrada->id) }}">
                                    <button class="btn btn-info btn-sm">
                                        <i class="mdi mdi-eye"></i>
                                    </button>
                                </a>

                                @if (session()->get('usuario_vinculo')->id_nivel <= 1)
                                    <form class="m-0"
                                        action="{{ route('ativo.estoque.entrada.destroy', $produtos_entrada->id) }}"
                                        method="POST">
                                        @csrf

                                        @method('delete')
                                        <button class="btn btn-danger btn-sm" data-placement="top" type="submit"
                                            title="Excluir"
                                            onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                            <i class="mdi mdi-delete"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                            <!--
    @endif -->

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

@endsection
