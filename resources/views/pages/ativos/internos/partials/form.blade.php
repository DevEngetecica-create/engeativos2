        
    <div class="row">

        <div class="col-md-6">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Dados do Ativo</h3>
                </div>

                    <div class="card-body">
                        
                        <div class="form-group">
                            <label class="form-label" for="patrimonio">Patrimônio</label>
                            <input class="form-control @error('patrimonio') is-invalid @enderror" id="patrimonio" name="patrimonio" type="text" value="{{ $ativo->patrimonio ?? $nextPatrimony }}" placeholder="Patrimônio" readonly>
                        </div>
                        
                        <div class="form-group">
                            
                                <label for="obra_id">Obra </label> <button class="badge badge-primary" data-toggle="modal" data-target="#modal-add" type="button"><i class="mdi mdi-plus"></i></button>
                                <select class="form-select select2 @error('obra_id') is-invalid @enderror" id="obra_id" name="obra_id" required>
                                    <option value="">Selecione uma obra</option>
                        
                                    @if (url()->current() == route('ativo.interno.create'))
                                        @foreach ($obras as $obra)
                                            <option value="{{ $obra->id }}">{{ $obra->codigo_obra }} | {{ $obra->razao_social }}</option>
                                        @endforeach
                                    @else
                                        @foreach ($obras as $obra)
                                            <option value="{{ $obra->id }}" {{ $ativo->obra_id == $obra->id ? 'selected=selected' : '' }}>{{ $obra->codigo_obra }} | {{ $obra->razao_social }}</option>
                                        @endforeach
                                    @endif
                        
                                </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="titulo">Título</label>
                                <input class="form-control @error('titulo') is-invalid @enderror" id="titulo" name="titulo" type="text" value="{{ $ativo->titulo ?? old('titulo') }}" placeholder="Título" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="numero_serie">Nº de série</label>
                            <input class="form-control @error('numero_serie') is-invalid @enderror" id="numero_serie" name="numero_serie" type="text" value="{{ $ativo->numero_serie ?? old('numero_serie') }}" placeholder="Nº de série" required>
                            
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="valor_atribuido">Valor</label>
                            <input class="form-control money @error('valor_atribuido') is-invalid @enderror" id="valor_atribuido" name="valor_atribuido" type="text" value="{{ $ativo->valor_atribuido ?? old('valor_atribuido') }}" required>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="marca">Marca </label> <button class="badge badge-primary" data-toggle="modal" data-target="#modal-marcas" type="button"><i class="mdi mdi-plus"></i></button>
                            <select class="form-select select2 @error('marca') is-invalid @enderror" id="marca" name="marca" required>
                                <option value="">Selecione uma marca</option>
                    
                                @if (url()->current() == route('ativo.interno.create'))
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->marca }}">{{ $marca->marca }}</option>
                                    @endforeach
                                @else
                                    @foreach ($marcas as $marca)
                                        <option value="{{ $marca->marca }}" {{ $ativo->marca == $marca->marca ? 'selected=selected' : '' }}>{{ $marca->marca }}</option>
                                    @endforeach
                                @endif
                    
                            </select>
                        </div>


                        <div class="form-group">
                            <label class="form-label" for="status">Ativo? </label>
                            <select class="form-control form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                @if (url()->current() == route('ativo.interno.create'))
                                    <option value="1">Ativo</option>
                                    <option value="0">Inativo</option>
                                @else
                                    <option value="1">Ativo</option>
                                    <option value="0" {{ @$ativo->status == 0 ? 'selected=selected' : '' }}>Inativo</option>
                                @endif
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label class="form-label" for="descricao">Descrição do Ativo</label>
                            <textarea class="form-control @error('descricao') is-invalid @enderror" id="descricao" name="descricao" cols="30" rows="10" required>{{ $ativo->descricao ?? old('descricao') }}</textarea>
                        </div>

                    </div>
            </div>
        </div>


        <div class="col-md-6">
            
                <div class="card card-success mb-2">
                <div class="card-header">
                    <h3 class="card-title">Imagem do Produto</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="form-group">
                            <div>
                                <label for="formFile" class="form-label">
                                    <h5> Imagem (300 x 300)</h5>
                                </label>
                                <input class="form-control" type="file" name="imagem" id="imagem" value="{{ $estoque->imagem ??  old('imagem') }}" onChange="carregarImg()">
                                <span class="text-danger">Extensões de imagens permitidas = 'png', 'jpg', 'jpeg', 'gif'</span>
                            </div>
                        </div>
                        <div class="form-group my-3">
                            @if($ativo->imagem ?? @$estoque->imagem)
                            <img src="{{url('storage/imagem_ativo_interno')}}/{{$ativo->imagem}}" id="target" class="img-thumbnail" class="img-fluid" style="min-width: 500px; min-height: 300px;">
                            @else
                            <img src="{{url('storage/imagem_ativo_interno/nao-ha-fotos.png')}}" id="target" class="img-thumbnail" class="img-fluid" style="min-width: 500px; min-height: 300px;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card card-success">
                <div class="card-header">
                    <h3 class="card-title">Nota Fiscal</h3>
                </div>
                <div class="card-body">
                     <div class="row">
                         <div class="form-group col-3">
                            <input class="form-control" name="titulo_nf" type="text" placeholder="Nº da Nota fiscal" required>
                        </div>
                        
                        <div class="form-group">
                            <textarea class="form-control" name="descricao_nf" type="text" placeholder="Descrição do anexo" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                          <label for="formFile" class="form-label">Arquivo da Nota Fiscal</label>
                          <input class="form-control" type="file" id="arquivo" name="arquivo">
                        </div>

                    </div>
                </div>
            </div>
        </div>
        
    </div>
        
    
        
       