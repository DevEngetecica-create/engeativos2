
    @if(session('failed'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            {{ session('failed') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

        <div class="card card-info mb-3">
            <div class="d-flex justify-content-start card-header bg-warning py-0">
                <h3 class="card-title text-black ">Anexos
                    <button class="btn btn-success btn-sm mx-3" data-toggle="modal" data-target="#modal-file" type="button" style="top:5px; position:relative" title="Adicionar anexo"><i class="mdi mdi-plus mdi-18px"></i></button>
                </h3>
            </div>
            
            <table class="table-hover table-striped table">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descrição</th>
                        <th>Tipo</th>
                        <th>Data Cad.</th>
                        <th width="10%">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anexos as $anexo)
                        <tr>
                           
                            <td class="align-middle">{{ $anexo->titulo ?? NULL}}</td>
                            <td class="align-middle">{{ $anexo->descricao ?? NULL}}</td>
                            <td class="align-middle">{{ $anexo->tipo ?? NULL}}</td>
                            <td class="align-middle">{{ Tratamento::datetimeBr($anexo->created_at) ?? NULL}}</td>
                            <td class="d-flex gap-2 align-middle">
                                
                                <a  class="m-auto" href="{{ url('uploads/anexos_ativos_internos/' . $anexo->titulo) }}" title="Baixar Anexo">
                                    <button class="btn btn-warning btn-sm" >
                                        <i class="mdi mdi-arrow-collapse-down mdi-18px"></i>
                                    </button>
                                </a>
                                    
                                <form class="m-auto" action="{{ route('ativo.interno.destroy', $anexo->id) }}" method="POST">
                                    @csrf
                                    @method('delete')
                                    <button class="btn btn-danger btn-sm m-0" data-toggle="tooltip" data-placement="top" type="submit" title="Excluir">
                                        <i class="mdi mdi-delete mdi-18px"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
     


<form id="file-form" action="{{ route('ativo.interno.store.file') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="modal-file" aria-hidden="true" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="tipo">Tipo</label>
                        <select class="form-control" name="tipo" required>
                            <option value="">Tipo de anexo</option>
                            <option value="Recibo de Compra">Recibo de Compra</option>
                            <option value="Recibo de Manutenção">Recibo de Manutenção</option>
                            <option value="Declaração de Descarte">Declaração de Descarte</option>
                            <option value="Outros">Outros</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="titulo">Título do anexo</label>
                        <input class="form-control" name="titulo" type="text" placeholder="Título do anexo" required>
                    </div>
                    <div class="form-group">
                        <label for="descricao">Descrição</label>
                        <textarea class="form-control" name="descricao" type="text" placeholder="Descrição do anexo" required></textarea>
                    </div>
                    <div class="form-group">
                        <div class="input-group">
                            <div class="custom-file">
                                <input class="custom-file-input" name="file" type="file" required>
                                <label class="custom-file-label" for="file">Escolha o arquivo</label>
                            </div>
                        </div>
                        <span class="text-muted"> Formatos válidos: *.PDF, *.XLS, *.XLSx, *.JPG, *.PNG, *.JPEG, *.GIF Tamanho Máximo: 64M</span>
                    </div>
                    <input name="id_ativo_interno" type="hidden" value="{{ $ativo->id ?? $data->id}}">
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Inserir</button>
                </div>
            </div>
        </div>
    </div>
</form>
