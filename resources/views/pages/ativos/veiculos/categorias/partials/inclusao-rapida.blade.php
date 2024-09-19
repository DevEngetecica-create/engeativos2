<div class="modal fade" id="modal-add" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form action="{{ route('cadastro.obra.fast.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <h5 class="mb-5">
                        Inclusão Rápida
                    </h5>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="id_empresa">Empresa</label>
                                <select class="form-control" id="id_empresa" name="id_empresa" required>
                                    <option value="">Selecione uma Empresa</option>
                                    @foreach ($empresas as $empresa)
                                        <option value="{{ $empresa->id }}" @php if(old('id_empresa', @$store->id_empresa) == $empresa->id) echo "selected"; @endphp>
                                            {{ $empresa->cnpj }} - {{ $empresa->razao_social }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="form-group">
                                <label for="razao_social">Razão Social</label>
                                <input class="form-control" id="razao_social" name="razao_social" type="text" placeholder="Razão Social" required>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="codigo_obra">Código da Obra</label>
                                <input class="form-control" id="codigo_obra" name="codigo_obra" type="text" value="{{ 'SGAE-' . date('YmI') }}" placeholder="Código da obra" required>
                            </div>
                        </div>
                        <div class="text-right">
                            <button class="btn btn-success" type="submit">Adicionar</button>
                            <button class="btn btn-secondary" data-dismiss="modal" type="button">Fechar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
