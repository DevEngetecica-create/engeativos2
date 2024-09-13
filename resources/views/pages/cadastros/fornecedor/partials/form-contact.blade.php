<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body">
                <h4 id="contatos">Contatos</h4>

                <table class="table-striped mb-5 table">
                    <tr>
                        <th>Setor</th>
                        <th>Nome do responsável</th>
                        <th>E-mail</th>
                        <th>Telefone</th>
                        <th>-</th>
                    </tr>

                    @forelse ($contatos as $contato)
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
                    @empty
                        <tr>
                            <td class="text-center" colspan="4">Nenhum contato cadastrado</td>
                        </tr>
                    @endforelse
                </table>

                <h4 class="mt-5">Adicionar novo contato</h4>

                <form action="{{ route('fornecedor.contato.store') }}" method="post">
                    @csrf
                    <div class="row mt-2">
                        <div class="col-md-2">
                            <select class="form-select" id="setor" name="setor" required>
                                <option value="">Selecione o setor</option>
                                <option value="Financeiro" {{ old('setor') === 'Financeiro' ? 'selected' : '' }}>Financeiro</option>
                                <option value="Administrativo" {{ old('setor') === 'Administrativo' ? 'selected' : '' }}>Administrativo</option>
                                <option value="Recursos Humanos" {{ old('setor') === 'Recursos Humanos' ? 'selected' : '' }}>Recursos Humanos</option>
                                <option value="Diretoria" {{ old('setor') === 'Diretoria' ? 'selected' : '' }}>Diretoria</option>
                                <option value="Vendas" {{ old('setor') === 'Vendas' ? 'selected' : '' }}>Vendas</option>
                                <option value="Outros" {{ old('setor') === 'Outros' ? 'selected' : '' }}>Outros</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" id="nome" name="nome" type="text" value="{{ old('nome') }}" placeholder="Nome do Responsável" required>
                        </div>
                        <div class="col-md-3">
                            <input class="form-control" id="email" name="email" type="email" value="{{ old('email') }}" placeholder="E-mail" required>
                        </div>
                        <div class="col-md-2">
                            <input class="form-control celular" id="telefone" name="telefone" type="text" value="{{ old('telefone') }}" required>
                        </div>
                        <div class="col-md-2">
                            <input name="id_fornecedor" type="hidden" value="{{ $store->id }}" required>
                            <button class="btn btn-gradient-primary btn-lg font-weight-medium" type="submit">Cadastrar contato</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
