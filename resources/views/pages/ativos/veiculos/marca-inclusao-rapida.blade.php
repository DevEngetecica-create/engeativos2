<div class="modal fade" id="addMarcaModal" role="dialog" aria-labelledby="addMarcaModalLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addMarcaModalLabel">Adicionar nova marca</h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('adicionar.marca') }}" method="POST">
                    @csrf
                    <input id="_token_modal" name="newToken" type="hidden" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="form-label" for="add_marca_da_maquina">Nome da marca</label>
                        <input class="form-control" id="add_marca_da_maquina" name="add_marca_da_maquina" type="text">
                    </div>
                    <button class="btn btn-primary" type="submit">Adicionar</button>
                </form>
            </div>
        </div>
    </div>
</div>
