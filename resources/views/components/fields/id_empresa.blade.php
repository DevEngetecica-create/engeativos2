<label for="id_empresa" class="form-label">Empresa</label>
<select name="id_empresa" id="id_empresa" class="form-select select2">
    <option value="">Selecione uma Empresa</option>
    @foreach ($empresas as $empresa)
        <option value="{{ $empresa->id }}" 
            @php if(old('id_empresa', @$store->id_empresa) == $empresa->id) echo "selected"; @endphp>
            {{ $empresa->cnpj }} - {{ $empresa->razao_social }}
        </option>
    @endforeach
</select>