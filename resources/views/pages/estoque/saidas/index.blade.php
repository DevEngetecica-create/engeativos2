@extends('dashboard')
@section('title', 'Ativos Internos')
@section('content')


<div class="row">
    <div class="col-2 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-center">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-office-building-cog mdi-24px"></i>
            </span> Saída - Estoque
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Estoque <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>
</div>

<hr>

<form action="{{ route('ativo.estoque.saida.index') }}" method="GET" class="mb-4">
    @csrf
    <div class="row my-4">
        <div class="col-2">
            <h3 class="page-title text-left">
                @if (session()->get('usuario_vinculo')->id_nivel <= 2) 
                    <a href="{{ route('ativo.estoque.saida.create') }}">
                        <span class="btn btn-sm btn-success">Novo Registro</span>
                    </a>
                    @endif
            </h3>
        </div>

        <div class="col-10">
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <input type="text" class="form-control shadow" name="produto" placeholder="Pesquisar produto" value="{{ request()->produto }}">
                </div>
                <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                    <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i class="mdi mdi-file-search-outline mdi-24px"></i></button>

                    <a href="{{ route('ativo.estoque.saida.index') }}" title="Limpar pesquisa">
                        <span class="btn btn-warning btn-sm py-0 shadow"><i class="mdi mdi-delete-outline mdi-24px"></i></span>
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
                    <th>Nome do solicitante</th>                   
                    <th class="text-center">Qtde retir.</th>
                    <th class="text-center">Valor unit.</th>
                    <th class="text-center">Valor total</th>    
                    
                    <th class="text-center">Data saída.</th>

                    @if (session()->get('usuario_vinculo')->id_nivel <= 2) 
                        <th class="text-center" width="10%">Ações</th>
                    @endif

                </tr>
            </thead>
            <tbody>

            {{--dd($produtos_saidas)--}}

                @foreach ($produtos_saidas as $produto_saida)
                <tr>
                    <td class="text-center align-middle">{{ $produto_saida->id }}</td>
                    <td class="align-middle">{{ $produto_saida->obra->nome_fantasia }}</td>

                    <td class="align-middle">{{ $produto_saida->categoria->titulo }}</td>

                    <td class="align-middle">{{ $produto_saida->produto->nome_produto }}</td>
                    <td class="align-middle">{{ $produto_saida->funcionario->nome }}</td>

                    <td class="align-middle text-center">{{ $produto_saida->quantidade_saida }}</td>

                    <td class="text-center">R$ {{ number_format($produto_saida->valor_unitario_saida, 2, ',', '.') }}</td>
                    <td class="text-center ">R$ {{ number_format($produto_saida->valor_total_saida, 2, ',', '.') }}</td>
                    
                    

                    <td class="text-center" class="align-middle">{{ Tratamento::datetimeBr($produto_saida->created_at) }}</td>

                  
                    <!--  @if (session()->get('usuario_vinculo')->id_nivel <= 2) -->
                    <td class="d-flex  justify-content-center gap-2 align-middle">

                        <a class="m-0" href="{{ route('ativo.estoque.edit', $produto_saida->id) }}">
                            <button class="btn btn-warning btn-sm">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                        </a>

                        <a class="m-0" href="{{ route('ativo.estoque.show', $produto_saida->id) }}">
                            <button class="btn btn-info btn-sm">
                                <i class="mdi mdi-eye"></i>
                            </button>
                        </a>

                        <form class="m-0" action="{{ route('ativo.estoque.destroy', $produto_saida->id) }}" method="POST">
                            @csrf
                            @method('delete')
                            <button class="btn btn-danger btn-sm" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                <i class="mdi mdi-delete"></i>
                            </button>
                        </form>
                    </td>
                    <!-- @endif -->
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



@endsection