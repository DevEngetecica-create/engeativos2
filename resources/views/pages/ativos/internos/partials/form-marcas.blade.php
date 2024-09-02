<form id="marcas-form">
    <div class="modal fade" id="modal-marcas" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center">
                    <div class="form-group">
                        <label for="marca">Marca</label>
                        <input class="form-control" id="marcas_modal" name="marca" type="text" placeholder="Nome da marca" required>
                    </div>
                    <input id="_token_modal" name="newToken" type="hidden" value="{{ csrf_token() }}">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Cadastrar</button>
                </div>
            </div>
        </div>
    </div>
</form>
