<label for="id_funcionario" class="form-label">Funcionário</label>
<select name="id_funcionario" id="id_funcionario" class="form-select select2">
    <option value="">Selecione um Funcionário</option>
    @foreach ($funcionarios as $funcionario)
    <option value="{{ $funcionario->id }}" @php if(old('id_funcionario', @$store->id_funcionario) == $funcionario->id) echo "selected"; @endphp>
        {{ $funcionario->matricula }} - {{ $funcionario->nome }}
    </option>
    @endforeach
</select>