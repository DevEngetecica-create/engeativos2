<div class="row">
    <div class="col-md-12">
        {{-- @dd($ativo_configuracoes->relacionamento) --}}
        <label class="form-label" for="id_ativo_configuracao">Categoria</label>
        <select class="form-control select2" id="id_ativo_configuracao" name="id_ativo_configuracao">

            <option value="">Selecione uma Categoria</option>
            @if (url()->current() == route('ativo.externo.adicionar'))


            @foreach ($ativo_configuracoes as $configuracao) 

                @if ($configuracao->id_relacionamento == 0)
                    <optgroup label="{{ $configuracao->titulo }}" readonly>
                @else
                <option value="{{ $configuracao->id }}">{{ $configuracao->titulo }}</option>
                @endif

            @endforeach
                @else
                @foreach ($ativo_configuracoes as $configuracoes)
                <option value="{{ $configuracoes->id }}" {{ $ativo->ativo->id_ativo_configuracao == $configuracoes->id ? 'selected' : '' }}>{{ $configuracoes->titulo }}</option>
                @endforeach
                @endif
        </select>
    </div>
</div>

<div class="row mt-3">
    <div class="col-md-12">
        <label class="form-label" for="id_obra">Obra</label>
        <select class="form-select select2" id="id_obra" name="id_obra">
            <option value="">Selecione uma Obra</option>
            @if (url()->current() == route('ativo.externo.adicionar'))
            @foreach ($obras as $obra)
            <option value="{{ $obra->id }}">
                {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
            </option>
            @endforeach
            @else
            @foreach ($obras as $obra)
            <option value="{{ $obra->id }}" {{ $ativo->id_obra == $obra->id ? 'selected' : '' }}>
                {{ $obra->codigo_obra }} - {{ $obra->razao_social }}
            </option>
            @endforeach
            @endif

        </select>
    </div>
</div>


<div class="row mt-3">
    <div class="col-md-6">
        <label class="form-label" for="titulo">Título</label>
        <input class="form-control" id="titulo" name="titulo" type="text" value="{{ $ativo->ativo->titulo ?? old('titulo') }}">
    </div>
</div>

<div class="row" id="div_calibracao" style="display: none;">
    <div class="col-3">
        <label class="form-label">Marca</label>
        <input class="form-control" id="marcaCalibra" name="marcaCalibra" type="text" value="{{ $item->marca ?? old('marca') }}">
    </div>

    <div class="col-3">
        <label class="form-label">Modelo</label>
        <input class="form-control " id="modeloCalibra" name="modeloCalibra" type="text" value="{{ $ativo->modelo ?? old('modelo') }}">
    </div>

    <div class="col-md-6">
        <label class="form-label">Nº de Série</label>
        <input class="form-control " id="n_serie" name="n_serie" type="text" value="{{ $ativo->n_serie ?? old('n_serie') }}">
    </div>

</div>

<div class="row mt-3">
    <div class="col-md-2">
        <label class="form-label" for="status">Quantidade</label>
        <input class="form-control" id="quantidade" name="quantidade" type="number" onchange="valorQuantidade(this)">
    </div>

    <div class="col-md-2">
        <label class="form-label" for="status">Valor</label>
        <input class="form-control " id="valor" name="valor" type="text" value="0">
    </div>

    <div class="col-md-2">
        <label class="form-label" for="calibracao">Precisa Calibrar?</label>
        <select class="form-select select2" id="calibracao" name="calibracao">
            @if (url()->current() == route('ativo.externo.adicionar'))
            <option value="Não">Não</option>
            <option value="Sim">Sim</option>

            @else
            <option value="Sim">Sim</option>
            <option value="Não" {{ $ativo->calibracao == "Não" ? 'selected' : '' }}>Não</option>
            @endif
        </select>
    </div>

    <div class="col-md-5 col-sm-6 col-12" id="menssagem_alert" style="display: none;">

        <div class="card mb-3" style="max-width: 540px;">
            <div class="row g-0 ">
                <div class="d-flex justify-content-center align-items-center col-md-2 bg-warning">
                    <i class="mdi mdi-alert mdi-48px"></i>
                </div>
                <div class="col-md-9">
                    <div class="card-body p-1 text-center">
                        <h5 class="card-title">ATENÇÃO</h5>
                        <p class="card-text">É possivel cadastra apenas um item por vez devido a necessidade da identificação do Nº de Série</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row my-4">
    <div class="col-md-3">
        <label class="form-label" for="status">Status</label>
        <select class="form-select select2" id="status" name="status">
            <option value="Ativo" selected>Em Estoque</option>
        </select>
    </div>
</div>