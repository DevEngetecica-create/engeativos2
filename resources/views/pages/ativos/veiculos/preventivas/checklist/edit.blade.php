@extends('layouts.app')


@section('content')
    <div class="container">
        <h1>Detalhes da Manutenção Preventiva</h1>
        <div class="card">
            <div class="card-header">
                <h3>{{ $preventiva->nome_preventiva }}</h3>
                <a href="{{ route('veiculo_preventivas.index') }}" class="btn btn-primary">Voltar para a Lista</a>
            </div>
        <form action="{{ route('veiculo_preventivas_checklist.update', $checklist->id) }}" method="POST" enctype="multipart/form-data" id="checklistForm">
            @php
                $nomeServicos = json_decode($checklist->nome_servico, true) ?? [];

                $situacaoPreventiva = $checklist->situacaoPreventiva;
                $situacao_checklist = json_decode($checklist->situacao, true) ?? [];
               // $situacoes = $checklist->situacaoPreventiva;
                $observacao = json_decode($checklist->observacoes, true) ?? [];
                $file = json_decode($checklist->files, true) ?? [];
               
            @endphp
            <div class="card-body">                
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="id_manut_preventiva" value="{{ $preventiva->id }}">

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
                                            $situacaoPreventiva = $situacaoPreventiva[$index] ?? 0;                                                
                                            $situacao = $situacao_checklist[$index] ?? '';

                                            //$situacao = $situacoes[$index] ?? 0;
                                            $observacaoAtual = $observacao[$index] ?? '';
                                            $fileAtual = $file[$index] ?? null;

                                        @endphp
                                        @switch($situacaoPreventiva)
                                            @case(1)
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="form-check form-switch">
                                                        <label class="form-check-label" for="obgr_{{ $index }}"><span style="font-size:18px;" title="Obrigatória">&#9899;</span></label>
                                                        <input style="width:40px; height:20px" type="checkbox" id="obgr_{{ $index }}" class="form-check-input checklist-checkbox" name="checklist[]" value="1" {{ $situacao == 1 ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="upload-btn-wrapper ms-2">
                                                        <span class="btn-upload"><i class="fas fa-cloud-upload-alt" style="font-size:25px;"></i></span>
                                                        <input class="observacao disabled" type="file" id="file{{ $index }}" name="file[]" {{-- $fileAtual ? '' : 'disabled' --}}>
                                                    </div>
                                                    <span data-bs-toggle="modal" data-bs-target="#executar{{ $index }}" class="observacao text-secondary disabled mx-2" style="font-size:25px;" title="Observações"><i class="fas fa-comment-alt"></i></span>
                                                </div>
                                                <div class="modal fade" id="executar{{ $index }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">{{ $nome_servico }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="exampleFormControlTextarea1" class="form-label">Observações</label>
                                                                    <textarea class="form-control" id="observacoes_{{ $index }}" name="observacoes[]" rows="3">{{ $observacaoAtual }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Inserir</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @break
                                            @case(2)
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="align-items-center form-check form-switch">
                                                        <label class="form-check-label" for="cond_{{ $index }}"><span style="font-size:18px;" title="Executar conforme condição">&#9673;</span></label>
                                                        <input style="width:40px; height:20px" type="checkbox" id="cond_{{ $index }}" class="form-check-input checklist-checkbox" name="checklist[]" value="2" {{ $situacao == 1 ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="upload-btn-wrapper ms-2">
                                                        <span class="btn-upload"><i class="fas fa-cloud-upload-alt" style="font-size:25px;"></i></span>
                                                        <input class="observacao disabled" type="file" id="file{{ $index }}" name="file[]" {{-- $fileAtual ? '' : 'disabled' --}}>
                                                    </div>
                                                    <span data-bs-toggle="modal" data-bs-target="#condicao{{ $index }}" class="observacao text-secondary disabled mx-2" style="font-size:25px;" title="Observações"><i class="fas fa-comment-alt"></i></span>
                                                </div>
                                                <div class="modal fade" id="condicao{{ $index }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">{{ $nome_servico }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="exampleFormControlTextarea1" class="form-label">Observações</label>
                                                                    <textarea class="form-control" id="observacoes_{{ $index }}" name="observacoes[]" rows="3">{{ $observacaoAtual }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Inserir</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                @break
                                            @case(3)
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <div class="align-items-center form-check form-switch">
                                                        <label class="form-check-label" for="veri_{{ $index }}"><span style="font-size:18px;" title="Conferir/Verificar">&#9650;</span></label>
                                                        <input style="width:40px; height:20px" type="checkbox" id="veri_{{ $index }}" class="form-check-input checklist-checkbox" name="checklist[]" value="1" {{ $situacao == 1 ? 'checked' : '' }}>
                                                    </div>
                                                    <div class="upload-btn-wrapper ms-2">
                                                        <span class="btn-upload"><i class="fas fa-cloud-upload-alt" style="font-size:25px;"></i></span>
                                                        <input class="observacao disabled" type="file" id="file{{ $index }}" name="file[]" {{-- $fileAtual ? '' : 'disabled' --}}>
                                                    </div>
                                                    <span data-bs-toggle="modal" data-bs-target="#verificar{{ $index }}" class="observacao text-secondary disabled mx-2" style="font-size:25px;" title="Observações"><i class="fas fa-comment-alt"></i></span>
                                                </div>
                                                <div class="modal fade" id="verificar{{ $index }}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="exampleModalLabel">{{ $nome_servico }}</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="mb-3">
                                                                    <label for="exampleFormControlTextarea1" class="form-label">Observações</label>
                                                                    <textarea class="form-control" id="observacoes_{{ $index }}" name="observacoes[]" rows="3">{{ $observacaoAtual }}</textarea>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Inserir</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @break
                                        @endswitch
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="submit" class="btn btn-primary">Salvar Checklist</button>                
            </div>
        </form>
        </div>
    </div>
<!-- Inclua o jQuery, o Bootstrap JS e o Toastr JS aqui -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.observacao').addClass('disabled');

            function toggleObservacao(checkbox) {
                var target = $(checkbox).closest('td').find('.observacao');
                var target_upload = $(checkbox).closest('td').find('.btn-upload');

                if (checkbox.checked) {
                    target.removeClass('disabled');
                    target.removeAttr('readonly').removeClass('text-secondary disabled').addClass('text-warning');
                    target_upload.removeAttr('readonly').removeClass('text-secondary disabled').addClass('text-success');
                } else {
                    target.addClass('disabled');
                    target.attr('readonly', true).removeClass('text-warning').addClass('text-secondary disabled');
                    target_upload.attr('readonly', true).removeClass('text-success').addClass('text-secondary disabled');
                }
            }

            $('.checklist-checkbox').change(function() {
                toggleObservacao(this);
            });

            // Função para ajustar o valor da checkbox antes do envio do formulário
                $('#checklistForm').on('submit', function() {
                    $('.checklist-checkbox').each(function() {
                        var checkbox = $(this);
                        if (checkbox.is(':checked')) {
                            checkbox.val(1);  // Checkbox marcada, valor igual a 1
                        } else {
                            checkbox.prop('checked', true); // Marca o checkbox para que ele seja enviado no submit
                            checkbox.val(0);  // Checkbox desmarcada, valor igual a 5
                        }
                    });
                });

            $('.checklist-checkbox').each(function() {
                toggleObservacao(this);
            });

            $(document).on('click', '.observacao.disabled', function(event) {
                event.preventDefault();
                return false;
            });
        });
    </script>
    <script>
        function validateFileInput(input) {
            const allowedExtensions = ['jpg', 'png', 'pdf', 'doc', 'docx'];
            const file = input.files[0];
            if (file) {
                const fileExtension = file.name.split('.').pop().toLowerCase();
                if (!allowedExtensions.includes(fileExtension)) {
                    alert("Extensão inválida. Extensões permitidas: " + allowedExtensions.join(', '));
                    input.value = '';
                }
            }
        }
    </script>
@endsection
