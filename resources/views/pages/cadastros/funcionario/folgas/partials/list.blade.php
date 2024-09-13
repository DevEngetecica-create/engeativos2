
        <div class="d-flex flex-wrap mt-5">
            @foreach ($folgaFuncionarios as $folgaFuncionario)
            
            <div class="card col-3 shadow p-3 mb-3 bg-body rounded mx-2">
                <div class="row g-0">
                    <div class="d-flex align-self-center justify-content-center col-md-3 ">
                      <i class="mdi mdi-account-check mdi-48px"></i>
                    </div>
                    <div class="col-md-9">
                      <div class="card-body p-2">
                        <h5 class="card-title">{{ $folgaFuncionario->funcionarios->nome}}</h5>
                        <p class="card-text">{{ $folgaFuncionario->obra->nome_fantasia ?? "-"}}</p>
                        <p class="card-text"> De <strong>{{ Tratamento::dateBr($folgaFuncionario->data_inico) }}</strong> até <strong>{{ Tratamento::dateBr($folgaFuncionario->data_fim) }}</strong></p>
                      
                      </div>
                    </div>
                </div>
                
                <div class="card-footer d-flex justify-content-center text-center {{ session()->get('usuario_vinculo')->id_nivel <= 1 ? 'd-block' : 'd-none' }}">
    
                    <a class="mr-2" href="{{ route('cadastro.funcionario.folga.editar', $folgaFuncionario->id) }}">
                        <button type="button" class="btn bg-warning btn-sm"><i class="mdi mdi-pencil " title="Editar"></i></button>
                    </a>
    
                    @if (session()->get('usuario_vinculo')->id_nivel ?? 1 <= 2) 
                        <form class="m-0 p-0" action="{{ route('cadastro.funcionario.folga.destroy', $folgaFuncionario->id) }}" method="POST">
                            @csrf
                            @method('delete')
                                <a class="excluir-padrao mx-2" data-id="$folgaFuncionario->id" data-table="empresas" data-module="cadastro/empresa">
                                    <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir" onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                        <i class="mdi mdi-delete"></i>
                                    </button>
                                </a>
                        </form>   
                    @endif
                </div>
            </div>
          
            @endforeach
        </div>
   
<div class="d-flex justify-content-end mt-3">
    <div class="paginacao">
        {{$folgaFuncionarios->render()}}
    </div>
</div>