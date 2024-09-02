<div class="row mt-3">

    @if (Session::get('obra')['id'] == null)
    <div class="col-6">
        <select class="form-select select2" name="id_obra_origem" required>
            <option value="">Selecione a Origem</option>
            @foreach ($obras as $obra)
            <option value="{{ $obra->id }}">
                {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
            </option>
            @endforeach
        </select>
    </div>
    @endif

    <div class="col-6">
        <select class="form-select select2" name="id_obra_destino" required>
            <option value="">Selecione o Destino</option>
            @foreach ($obras as $obra)
            <option value="{{ $obra->id }}">
                {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
            </option>
            @endforeach
        </select>
    </div>
</div>

<div class="row mt-3">
    <div class="col-8">
        <div class="form-group">
            <label for="id_ativo_externo">Pesquisar Item</label>
            <select class="form-control form-select select2" name="id_ativo_externo[]" required>
                <option>Pesquise o Item desejado</option>
                @foreach ($itens as $item)
                @if (count($item->estoque) > 0)
                <option value="{{ $item->id }}">{{ $item->titulo }} - em estoque {{ count($item->estoque) }}</option>
                @endif
                @endforeach
            </select>

        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            <label for="quantidade">Quantidade</label>
            <input class="form-control" name="quantidade[]" type="number" value="" min="0" required>
        </div>
    </div>
    <div class="col-2">
        <div class="form-group">
            <label id="botoes">Ações</label>
            <div id="botoes">
                <button class="btn btn-warning listar-ativos-adicionar" type="button"><i class="mdi mdi-plus"></i></button>
                <button class="btn btn-primary listar-ativos-remover" type="button"><i class="mdi mdi-minus"></i></button>
            </div>
        </div>
    </div>
</div>

<div id="listar-ativos-linha"></div>

<template id="listar-ativos-template">
    <div class="row template-row">
        <div class="col-8 mt-2">

            <select class="form-control template item-lista" name="id_ativo_externo[]">
                <option>Pesquise o Item desejado</option>
                @foreach ($itens as $item)
                <option value="{{ $item->id }}">{{ $item->titulo }} - em estoque {{ count($item->estoque) }}</option>
                @endforeach
            </select>

        </div>
        <div class="col-2 mt-2">

            <input class="form-control" name="quantidade[]" type="number" value="">

        </div>
        <div class="col-2 mt-2">

        </div>
    </div>
</template>

<div class="row mt-3">
    <div class="col-12">
        <label id="observacoes">Observações</label>
        <textarea class="form-control" id="observacoes" name="observacoes" rows="4"></textarea>
    </div>
</div>