@extends('dashboard')
@section('title', 'Contatos')
@section('content')

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary me-2 text-white">
            <i class="mdi mdi-access-point-network menu-icon"></i>
        </span> Contatos
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <span></span>Cadastros <i class="mdi mdi-check icon-sm text-primary align-middle"></i>
            </li>
        </ul>
    </nav>
</div>

<div class="page-header">
    <h3 class="page-title">
        <a href="{{ route('cadastro.fornecedor.adicionar') }}">
            <button class="btn btn-sm btn-danger">Novo Registro</button>
        </a>
    </h3>
</div>

<div class="row">
    <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 id="contatos">Contatos</h4>

                <table class="table-striped mb-5 table">
                    <tr>
                        <th>Setor</th>
                        <th>Nome do responsável</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>Ações</th>
                    </tr>

                    @foreach ($contatos as $contato)
                        <tr>
                            <td>{{ $contato->setor }}</td>
                            <td>{{ $contato->nome }}</td>
                            <td>{{ $contato->email }}</td>
                            <td>{{ $contato->telefone }}</td>
                            <td>
                                <form action="{{ route('fornecedor.contato.destroy', $contato->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="badge badge-danger" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                        <i class="mdi mdi-delete"></i> Excluir contato
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @forelse ($contatos as $contato)
                        @empty
                        <tr>
                            <td class="text-center" colspan="4">Nenhum contato cadastrado</td>
                        </tr>
                        @endforelse

                    @endforeach
                </table>

                </table>
            </div>
        </div>
    </div>
</div>

@endsection