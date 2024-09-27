{{-- <div class="modal fade" id="modal-funcao" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form id="funcao_rapida" action="{{ route('cadastro.funcoes.fast.store') }}" method="post">
                @csrf
                <div class="modal-body">
                    <h5 class="mb-5">
                        Inclusão Rápida
                    </h5>
                    <div class="row">
                        <div class="col-md-4">
                            <label class="form-label" for="funcao">Função</label>
                            <input class="form-control @error('funcao') is-invalid @enderror" name="funcao" type="text" value="{{ old('funcao') }}" placeholder="Nome da função" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label" for="codigo">Código CBO</label>
                            <input class="form-control @error('codigo') is-invalid @enderror" name="codigo" type="text" value="{{ old('codigo') }}" placeholder="Código CBO" required>
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
</div> --}}

<form id="funcao-form">
    <div class="modal fade" id="modal-funcao" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="funcao">Função</label>
                                <input class="form-control" id="funcao_modal" name="funcao" type="text" value="{{ old('funcao') }}" placeholder="Função" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label" for="codigo">Código CBO</label>
                                <input class="form-control" id="codigo_modal" name="codigo" type="text" value="{{ old('codigo') }}" placeholder="Código CBO" required>
                            </div>
                        </div>
                    </div>
                    <input id="_token_modal" name="newToken" type="hidden" value="{{ csrf_token() }}">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Cadastrar</button>
                </div>
            </div>
        </div>
    </div>
</form>
