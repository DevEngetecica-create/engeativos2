@extends('layouts.app')

<style>
    table td {
        font-size: small;
    }
</style>

@section('content')
    <div class="container">
        <h1>Detalhes da Manutenção Preventiva</h1>
        <div class="card">
            <div class="card-header">
                <h3>{{ $preventiva->nome_preventiva }}</h3>

                <a href="{{ route('veiculo_preventivas.index') }}" class="btn btn-primary mx-3">Voltar para a Lista</a>

            </div>
            <div class="card-body">
                <form action="{{ route('veiculo_preventivas_checklist.update', $checklist->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    @php
                    $nomeServicos = json_decode($checklist->nome_servico, true) ?? [];
                    $situacaoPreventiva = $checklist->situacaoPreventiva;
                    $situacao_checklist = json_decode($checklist->situacao, true) ?? [];
                    $observacao = json_decode($checklist->observacoes, true) ?? [];
                    $file = json_decode($checklist->files, true) ?? [];                  

                @endphp
                    <!-- Hidden fields for nome_servico and id_manut_preventiva -->
                 
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center">Nome do Serviço</th>
                                <th class="text-center">{{ $checklist->periodo }} Horas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nomeServicos as $index => $nome_servico)
                                <tr>
                                    <td>{{ $nome_servico }}</td>
                                    <td>
                                            @php
                                                $periodoServico = array_map('trim', explode(',', json_decode($preventiva->periodo)[$index]));
                                                $situacaoPreventiva = $situacaoPreventiva[$index] ?? 0;                                                
                                                $situacao = $situacao_checklist[$index] ?? '';
                                                $observacaoAtual = $observacao[$index] ?? '';
                                                $fileAtual = $file[$index] ?? null;
                                                //var_dump($situacao);
                                                    switch ($situacaoPreventiva) {
                                                        
                                                        case 1:
                                                            echo '<div class="d-flex justify-content-center form-check form-switch">
                                                                    <input disabled type="checkbox" id="obgr_' . $index .'" class="form-check-input" name="checklist[]" value="1" ' . ($situacao == 1 ? 'checked' : '') . ' style="width:33px; height:18px">
                                                                    <label class="form-check-label" for="obgr_' . $index .'"><span style="font-size:20px;">&#9899;</span></label>
                                                                 ' .

                                                                ($fileAtual != 'null' ? '<a id="download' . $index . '" href="' . asset('storage/' . $fileAtual) . '" target="_blank" class="ms-2"><i class="fa fa-download text-success" title="Baixar anexo" style="font-size:18px;"></i></a>' :  '<span class="ms-2"><i class="fa fa-download text-success" title="Não há arquivo anexado" style="font-size:18px;"></i></span>').

                                                                ($observacaoAtual ? '<span data-bs-toggle="modal" data-bs-target="#executar' . $index . '"><i class="fas fa-comment-alt text-warning mx-2" style="font-size:25px;" title="Observações"></i></span>' : ' </div>');

                                                            echo '<div class="modal fade" id="executar' . $index . '"  tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">'.$nome_servico.'</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="mb-3">
                                                                                    <label for="exampleFormControlTextarea1" class="form-label">Observações</label>
                                                                                    <textarea class="form-control" id="observacoes'. $index . '" " rows="3" readonly>'.$observacaoAtual.'</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Inserir</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>';

                                                            break;
                                                        case 2:
                                                            echo '<div class="d-flex justify-content-center form-check form-switch">
                                                                    <input disabled type="checkbox" id="condicao_' . $index .'" class="form-check-input" name="checklist[' . $index . ']" value="1" ' . ($situacao == 1 ? 'checked' : '') . ' style="width:33px; height:18px">
                                                                    <label class="form-check-label" for="condicao_' . $index . '"><span style="font-size:20px;">&#9673;</span></label>
                                                                ' .

                                                                ($fileAtual != 'null' ? '<a id="download' . $index . '" href="' . asset('storage/' . $fileAtual) . '" target="_blank" class="ms-2"><i class="fa fa-download text-success" title="Baixar anexo" style="font-size:18px;"></i></a>' :  '<span class="ms-2"><i class="fa fa-download text-success" title="Não há arquivo anexado" style="font-size:18px;"></i></span>').

                                                                ($observacaoAtual ? '<span data-bs-toggle="modal" data-bs-target="#condicao' . $index . '"><i class="fas fa-comment-alt text-warning mx-2" style="font-size:25px;" title="Observações"></i></span>' : ' </div>');

                                                            echo '<div class="modal fade" id="condicao' . $index . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                    <div class="modal-dialog">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="exampleModalLabel">{{ $nome_servico }}</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="mb-3">
                                                                                    <label for="exampleFormControlTextarea1" class="form-label">Observações</label>
                                                                                    <textarea class="form-control" id="observacoes_' . $index . '" rows="3" readonly>'.$observacaoAtual.'</textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Inserir</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>';

                                                            break;
                                                        case 3:
                                                            echo '<div class="d-flex justify-content-center form-check form-switch">
                                                                    <input disabled type="checkbox" id="verificar_' . $index .'" class="form-check-input" name="checklist[' . $index . ']" value="1" ' . ($situacao == 1 ? 'checked' : '') . ' style="width:33px; height:18px">
                                                                    <label class="form-check-label" for="verificar_' . $index .'"><span style="font-size:20px;">&#9650;</span></label>
                                                                ' .
                                                                ($fileAtual != 'null' ? '<a id="download' . $index . '" href="' . asset('storage/' . $fileAtual) . '" target="_blank" class="ms-2"><i class="fa fa-download text-success" title="Baixar anexo" style="font-size:18px;"></i></a>' :  '<span class="ms-2"><i class="fa fa-download text-success" title="Não há arquivo anexado" style="font-size:18px;"></i></span>').

                                                                ($observacaoAtual ? '<span data-bs-toggle="modal" data-bs-target="#verificar' . $index . '"><i class="fas fa-comment-alt text-warning mx-2" style="font-size:22px;" title="Observações"></i></span>' : ' </div>');

                                                            echo '<!-- Modal -->
                                                                    <div class="modal fade" id="verificar' . $index . '" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                        <div class="modal-dialog">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title" id="exampleModalLabel">'.  $nome_servico .'</h5>
                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                </div>
                                                                                <div class="modal-body">
                                                                                    <div class="mb-3">
                                                                                        <label for="exampleFormControlTextarea1" class="form-label">Observações</label>
                                                                                        <textarea class="form-control" id="observacoes' . $index .'" rows="3" readonly>' . $observacaoAtual . '</textarea>
                                                                                    
                                                                                    </div>
                                                                                </div>
                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Cancelar</button>                                                                              
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>';
                                                        break;
                                                                                                                       
                                                    }

                                            @endphp
                                        </td>                                   
                                </tr>
                            @endforeach
                        </tbody>
                    </table>                    
                </form>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        $(document).ready(function() {
            $('#checklistForm').on('submit', function() {
                $('.checklist-checkbox').each(function() {
                    var checkbox = $(this);
                    if (checkbox.is(':checked')) {
                        checkbox.val(1);
                    } else {
                        checkbox.prop('checked', true);
                        checkbox.val(0);
                    }
                });
            });
        });
    </script>
@endsection
