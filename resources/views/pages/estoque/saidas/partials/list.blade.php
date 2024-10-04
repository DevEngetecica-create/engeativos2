<table class="table table-hover align-middle table-nowrap table-sm mb-0" id="lista_produtos">
    <thead>
        <tr>
            <th class="text-center">ID</th>
            <th class="text-center">Obra</th>
            <th class="text-center">Produto</th>
            <th class="text-center">Lotes</th>
            <th class="text-center">Saldo</th>
        </tr>
    </thead>
    <tbody>
        @foreach($produtos_saidas as $produto)
        <tr>
            <td>
                <input type="checkbox" class="checkbox-container form-check-input" value="{{ $produto->id }}" id="id_produto{{ $produto->id }}" name="id_produto[]" style="height:15px; width:15px">
            </td>
            <td>{{ $produto->obra->nome_fantasia ?? 'N/A' }}</td>
            <td>{{ $produto->nome_produto }}</td>
            <td class="text-center">
                @if($produto->lotes_com_saldo->isEmpty())
                <span class="text-warning"><small>Não possui lote cadastrado</small></span>
                @else
                <select class="form-control lote-select" data-id="{{ $produto->id }}">
                    <option value="" selected>Selecionar</option>
                    @foreach($produto->lotes_com_saldo as $lote)
                    <optgroup label="Lote: {{ $lote['numeroLote'] }}">
                        <option value="{{ $lote['numeroLote'] }}" data-quantidade="{{ $lote['quantidadeDisponivel'] }}">
                            Val: {{ Tratamento::dateBr($lote['validade']) }} - Qtde: {{ $lote['quantidadeDisponivel'] }}
                        </option>
                    </optgroup>
                    @endforeach
                </select>
                @endif
            </td>
            <td class="text-center">{{$produto->saldoEstoque()}}
            
            </td>
        </tr>
        @endforeach

    </tbody>
</table>


<div class="row mt-3" disabled="disabled" readonly="true">
    <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 " disabled="disabled" readonly="true">
        <div class="paginacao" disabled="disabled" readonly="true">
            {{$produtos_saidas->render()}}
        </div>
    </div>
</div>