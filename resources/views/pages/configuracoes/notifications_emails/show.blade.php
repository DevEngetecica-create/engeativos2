@extends('dashboard')
@section('title', 'Notificações por email')
@section('content')

<div class="card p-5">
    <div class="card-body ">

        <div class="row ">
            <div class="col-6 breadcrumb-item active" aria-current="page">
                <h3 class="page-title text-left">
                    <span class="page-title-icon bg-gradient-primary me-2">
                        <i class="mdi mdi-cog-counterclockwise mdi-24px"></i>
                    </span> Grupos de notificações por email
                </h3>
            </div>

            <div class="col-4 active m-2">
                <h5 class="page-title text-left m-0">
                    <span>Edição <i class="mdi mdi-check icon-sm text-primary align-middle"></i></span>
                </h5>
            </div>
        </div>

        <hr>

        <div class="row mt-5">
            <div class="col-md-12 grid-margin stretch-card">
                <div class="card">
                    <div class="card-body">

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Ops!</strong><br><br>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @endif

                          
                            <div class="row">
                                <div class="col-2">
                                    <label class="form-label" for="codigo_obra">Onde será notificado?</label>
                                </div>
                                
                                <div class="col-9">
                                  
                                        
                                        @foreach ($modulos_permitidos as $module)
                                            @if (count($module['submodulos']) > 0)
                                                
                                                    @foreach ($module['submodulos'] as $sub)
                                                    
                                                    @if($notificacoes_emails->id_modulo == $sub['id'])
                                                    
                                                        <button type="button" class="btn btn-outline-primary"> {{ $sub['titulo'] }}</button>
                                                    
                                                    @endif
                                                       
                                                       
                                                    @endforeach
                                                
                                            @else
                                              
                                                    {{ $module['titulo'] }}
                                                
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            
                            <div class="row my-4">
                                <div class="col-2">
                                    <label class="form-label">Qual método?</label>
                                </div>
                                  
                                    @php
                                    $metodos = json_decode($notificacoes_emails->metodo, true);
                                     $metodos = is_array($metodos) ? $metodos : [];
                                    @endphp
                                
                                <div class="col-10">
                                    @foreach ($metodos as $id => $metodo)
                                    
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox"  checked>
                                        <label class="form-check-label" for="add">
                                            @if( $metodo == "add") Cadastrar  @elseif( $metodo == "edit")  Editar    @elseif( $metodo == "delete") Excluir @else @endif
                                        </label>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-2">
                                    <label class="form-label" for="codigo_obra">Nome do Grupo:</label>
                                </div>
                                
                                <div class="col-10 border-bottom">
                                    <span >{{ $notificacoes_emails->nome_grupo}}</span>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-2">
                                    <label class="form-label" for="codigo_obra">Usuarios:</label>
                                </div>
                                
                                <div class="col-10 border-bottom">
                                
                                    <div class="row">
                                        @foreach ($usuarios as $usuario)
                                            @foreach ($imagens_usuario as $imagem)
                                                @if ($imagem->id_usuario == $usuario->id)
                                                    @if ($imagem->vinculo_funcionario)
                                                        @if ($imagem->vinculo_funcionario->imagem_usuario)
                                                            <div class="col-4 h-100">
                                                                <div class="card-body text-center">
                                                                    <div class="avatar-md mb-3 mx-auto">
                                                                        <img src="{{ asset('build/images/users') }}/{{ $imagem->vinculo_funcionario->id }}/{{ $imagem->vinculo_funcionario->imagem_usuario }}" alt="" id="candidate-img" class="img-thumbnail rounded-circle shadow-none">
                                                                    </div>
                                                                    <h6 id="candidate-name" class="mb-0 text-capitalize">{{ $imagem->vinculo_funcionario->nome }}</h6>
                                                                    <p id="candidate-position" class="text-muted">{{ $imagem->vinculo_funcionario->funcao->funcao ?? 'sem reg' }}</p>
                                                                    <div class="card-footer">
                                                                        <span class="btn btn-success btn-sm rounded-pill w-sm">{{ $usuario->email }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="col-4 h-100">
                                                                <div class="card-body text-center">
                                                                    <div class="avatar-md mb-3 mx-auto">
                                                                        <img src="{{ asset('build/images/users/user-dummy-img.jpg') }}" alt="" id="candidate-img" class="img-thumbnail rounded-circle shadow-none">
                                                                    </div>
                                                                    <h6 id="candidate-name" class="mb-0">{{ $imagem->vinculo_funcionario->nome }}</h6>
                                                                    <p id="candidate-position" class="text-muted">{{ $imagem->vinculo_funcionario->funcao->funcao ?? 'sem reg' }}</p>
                                                                    <div class="card-footer">
                                                                        <span class="btn btn-primary btn-sm rounded-pill w-sm">{{ $usuario->email }}</span>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @else
                                                        <div class="col-4 h-100">
                                                            <div class="card-body text-center">
                                                                <div class="avatar-md mb-3 mx-auto">
                                                                    <img src="{{ asset('build/images/users/user-dummy-img.jpg') }}" alt="" id="candidate-img" class="img-thumbnail rounded-circle shadow-none">
                                                                </div>
                                                                <h6 id="candidate-name" class="mb-0">Nome não disponível</h6>
                                                                <p id="candidate-position" class="text-muted">Função não disponível</p>
                                                                <div class="card-footer">
                                                                    <span class="btn btn-primary btn-sm rounded-pill w-sm">{{ $usuario->email }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            

                            <div class="row mb-4">
                                <div class="col-2">
                                    <label for="obra_id">Obra de origem da Notificação: </label>
                                </div>
                                
                                <div class="col-10 border-bottom mb-3">
                                    

                                        @foreach ($obras as $obra)
                                            
                                             @if($notificacoes_emails->obra->id == $obra->id) <h6 class="mb-4">{{ $obra->codigo_obra }} | {{ $obra->razao_social }} </h6> @endif
                                             
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-2">
                                    <label class="form-label" for="id_status">Situação</label>
                                </div>
                                    
                                <div class="col-10 border-bottom mb-3">
                                  
                                        <h6 class="mb-4 btn btn-outline-{{ $notificacoes_emails->status->classe}}"> @if($notificacoes_emails->status->id == @$status->id) {{ $notificacoes_emails->status->titulo }} @endif </h6>
                                       
                                </div>
                            </div>
                           
                           
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
                             