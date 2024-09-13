
<div class="modal fade" id="modal-servicos" role="dialog" aria-labelledby="addServicoModalLabel" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addServicoModalLabel">Adicionar Servico</h5>
                <button class="close" data-dismiss="modal" type="button" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

            <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>Serviço</th>                          
                        </tr>
                    </thead>
                    <tbody>
                        @if(count( $tabela) >0)
                        @foreach( $tabela as $item)
                        <tr>
                            <td>{{$item->id }}</td>
                            <td>{{$item->nomeServico }}</td>                          
                            <td>
                                <a href="{{route('servicos.editar', $item->id) }}">
                                    <span class="badge badge-dark badge-sm">baixar</span>
                                </a>
                                <a href="{{ route('servicos.delete'', $item->id) }}">
                                    <span class="badge badge-danger badge-sm"><span class="mdi mdi-delete"></span></span>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="5">Nenhum registro encontrado.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
                <form action="{{ route('adicionar.servico') }}" method="POST">
                    @csrf
                    @method('creat')
                    <input id="_token_modal" name="newToken" type="hidden" value="{{ csrf_token() }}">
                    <div class="form-group">
                        <label class="form-label" for="servicos_modal">Nome do Serviço</label>
                        <input class="form-control" id="servicos_modal" name="nomeServico" type="text" placeholder="Serviço" required>
                    </div>
                    <button class="btn btn-secondary" data-dismiss="modal" type="button">Cancelar</button>
                    <button class="btn btn-primary" type="submit">Adicionar</button>
                </form>
            </div>
        </div>
    </div>
</div>
