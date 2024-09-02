<div class="row mt-3">
    <div class="col-md-3">
        <label class="form-label" for="funcao">Função</label>
        <input class="form-control @error('funcao') is-invalid @enderror" id="funcao" name="funcao" type="text" value="{{ $funcao->funcao ?? old('funcao') }}" placeholder="Nome da função" required>
    </div>

    <div class="col-md-2">
        <label class="form-label" for="codigo">Código CBO</label>
        <input class="form-control @error('codigo') is-invalid @enderror" id="codigo" name="codigo" type="text" value="{{ $funcao->codigo ?? old('codigo') }}" placeholder="Código CBO" required>
    </div>
