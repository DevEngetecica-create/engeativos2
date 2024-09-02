@extends('dashboard')
@section('title', 'Fornecedores')
@section('content')


<div class="row">
    <div class="col-3 breadcrumb-item active" aria-current="page">
        <h3 class="page-title text-center">
            <span class="page-title-icon bg-gradient-primary me-2">
                <i class="mdi mdi-office-building-cog mdi-24px"></i>
            </span> Fornecedores
        </h3>
    </div>

    <div class="col-4 active m-2">
        <h5 class="page-title text-left m-0">
            <span>Cadastros <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
        </h5>
    </div>

</div>

<hr>
<div class="row my-4">
    <div class="col-2">
        <h3 class="page-title text-left">
            @if (session()->get('usuario_vinculo')->id_nivel <= 2) 
                <a href="/admin/cadastro/fornecedor/adicionar">
                    <button class="btn btn-sm btn-success">Novo Registro</button>
                </a>
            @endif
        </h3>
    </div>
    <div class="col-10">
        <form action="{{ route('fornecedor') }}" method="GET">
            @csrf
            
                
            <div class="row justify-content-center">
                <div class="col-5 m-0 p-0 ">
                    <input type="text" class="form-control shadow" name="fornecedor" placeholder="Pesquisar categoria" value="{{ request()->fornecedor }}">
                </div>
                <div class="col-2 text-left  m-0 p-0 mb-2 mx-2">

                    <button type="submit" class="btn btn-primary btn-sm py-0 shadow" title="Pesquisar"><i class="mdi mdi-file-search-outline mdi-24px"></i></button>

                    <a href="{{ route('fornecedor') }}" title="Limpar pesquisa">
                        <span class="btn btn-warning btn-sm py-0 shadow"><i class="mdi mdi-delete-outline mdi-24px"></i></span>
                    </a>
                </div>
                <div class="col-1 text-left m-0">

                </div>
            </div>
        </form>
    </div>
</div>


<div class="card">
    <div class="card-body">

        <table class="table table-bordered table-hover align-middle table-nowrap mb-0">
            <thead>
                <tr>
                    <th class="text-center" width="8%">ID</th>
                    <th>CNPJ</th>
                    <th>Razão Social</th>
                    <th>WhatsApp</th>
                    <th>E-mail</th>
                    <th>Status</th>
                    @if (session()->get('usuario_vinculo')->id_nivel < 2)
                    <th width="10%">Ações</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($fornecedores as $fornecedor)
                <tr>
                    <td class="text-center">{{ $fornecedor->id }}</td>
                    <td>{{ $fornecedor->cnpj ?? '-' }}</td>
                    <td>{{ $fornecedor->razao_social }}</td>
                    <td>{{ $fornecedor->celular }}</td>
                    <td>{{ $fornecedor->email }}</td>
                    <td>{{ $fornecedor->status }} </td>
                    <td class="d-flex">
                        <button class="btn btn-success btn-sm mr-2" data-toggle="modal" data-target="#modal-contato-{{ $fornecedor->id }}" title="Ver contatos"><i class="mdi mdi-account-supervisor-circle mdi-18px"></i></button>

                        {{-- MODAL CONTATOS --}}
                        <div class="modal fade" id="modal-contato-{{ $fornecedor->id }}" aria-hidden="true" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                <div class="modal-content">
                                    <div class="modal-body">
                                        <p class="text-primary text-center"><strong>{{ $fornecedor->razao_social }} ({{ $fornecedor->cnpj ?? '-' }})</strong></p>
                                        <p><strong>Contatos</strong></p>
                                        <ul>

                                            @foreach ($fornecedor->contatos as $contato)
                                            <li class="">
                                                <strong>Setor: {{ $contato->setor }}</strong> <br>
                                                <table class="table">
                                                    <tr>
                                                        <td style="width: 33%">{{ $contato->nome }}</td>
                                                        <td style="width: 33%">{{ $contato->email }}</td>
                                                        <td style="width: 33%">{{ $contato->telefone }}</td>
                                                    </tr>
                                                </table>
                                            </li>
                                            @endforeach
                                        </ul>
                                        <div class="text-right">
                                            <a class="btn btn-gradient-primary btn-lg font-weight-medium" href="{{ route('cadastro.fornecedor.editar', $fornecedor->id) }}#contatos">Adicionar contatos</a>
                                            <button class="btn btn-gradient-danger btn-lg font-weight-medium" data-dismiss="modal" type="button">Fechar</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('cadastro.fornecedor.editar', $fornecedor->id) }}">

                            <button class="btn btn-warning btn-sm mx-2">
                                <i class="mdi mdi-pencil"></i>
                            </button>

                        </a>
                        @if (session()->get('usuario_vinculo')->id_nivel < 2) 
                            <form action="{{ route('cadastro.fornecedor.destroy', $fornecedor->id) }}" method="POST">
                                @csrf
                                @method('delete')
                                <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
        <div class="row mt-3">
            <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 ">
                <div class="paginacao">
                    {{$fornecedores->render()}}
                </div>
            </div>
        </div>
    </div>
</div>


@endsection