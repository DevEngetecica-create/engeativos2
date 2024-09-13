<table class="table table-hover align-middle table-nowrap table-sm mb-0">
    <thead style="background-color: crimson; color:aliceblue">
        <tr>
            <th class="text-center">ID</th>
            <th>Produto</th>
            <th>Fornecedor</th>
            <th>Marca</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($produtos_entradas as $produto)
            <tr>
                <td class="text-center"> <input type="checkbox" class="checkbox-container form-check-input"
                        value="{{ $produto->id }}" id="id_produto{{ $produto->id }}" name="id_produto_tabela[]"
                        style="height:15px; width:15px"> </small></td>
                <td><small>{{ $produto->nome_produto }} </small></td>
                <td><small>{{ $produto->fornecedor->nome_fantasia ?? "sem reg."}}</small></td>
                <td>
                    <small>
                        {{ $produto->marca->nome_marca }}
                    </small>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<small>
    <div class="row mt-3" disabled="disabled" readonly="true">
        <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 " disabled="disabled" readonly="true">
            <div class="paginacao" disabled="disabled" readonly="true">
                {{ $produtos_entradas->render() }}
            </div>
        </div>
    </div>
</small>
