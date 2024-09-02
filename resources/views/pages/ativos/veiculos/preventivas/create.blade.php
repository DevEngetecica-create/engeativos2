@extends('dashboard')

@section('content')
    <div class="card m-5">
        <div class="card-body m-3">
            <div class="card-header mb-3">
                <div class="row align-items-center">
                    <div class="col-6">
                        <h2 >Cadastrar Manutenção Preventiva</h2>
                    </div>
                    <div class="col-6">

                        <!-- Radio Buttons -->
                        <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" id="btnradio" autocomplete="on" checked="">
                            <label class="btn btn-outline-warning material-shadow-none" for="btnradio"  style="padding-top:12px">Legenda da situação</label>

                            <input type="radio" class="btn-check" id="btnradio1" autocomplete="off">
                            <label class="btn btn-outline-warning material-shadow-none" for="btnradio1" style="padding-top:12px"><span style="font-size: 14px;">&#9899;<span style="color: black">Obrigatória</span></label>

                            <input type="radio" class="btn-check" id="btnradio2" autocomplete="off">
                            <label class="btn btn-outline-warning material-shadow-none" for="btnradio2"><span style="font-size: 18px; color: black">&#9673; </span><span style="color: black">Executar conforme condição</span></label>

                            <input type="radio" class="btn-check" id="btnradio3" autocomplete="off">
                            <label class="btn btn-outline-warning material-shadow-none" for="btnradio3"><span style="font-size: 18px; color: black">&#9650; </span><span style="color: black">Conferir/ Verificar</span></label>
                        </div>
                    </div>
                </div>
            </div>

            <form action="{{ route('veiculo.manut_preventiva.store') }}" method="POST">
                @csrf

                <div class="row mb-3">
                    <div class="col-3">
                        <div class="form-group">
                            <label for="tipo_veiculo">Tipo de Veículo</label>
                            <select class="form-select" id="tipo_veiculo" name="tipo_veiculo" required>
                                <option value="">Selecione</option>
                                <option value="1" {{ old('tipo') == '1' ? 'selected' : '' }}>Carro</option>
                                <option value="2" {{ old('tipo') == '2' ? 'selected' : '' }}>Moto</option>
                                <option value="3" {{ old('tipo') == '3' ? 'selected' : '' }}>Caminhão</option>
                                <option value="maquina" {{ old('tipo') == '3' ? 'selected' : '' }}>Maquina</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="nome_preventiva">Nome da Preventiva</label>
                            <input type="text" name="nome_preventiva" id="nome_preventiva" class="form-control" required>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label>Adicionar ou Remover Campos</label>
                            <div>
                                <a class="listar-ativos-adicionar" id="listar-ativos-adicionar">
                                    <span class="btn btn-primary text-white py-1 px-2 rounded mx-2"><i
                                            class="mdi mdi-plus mdi-18px"></i></span>
                                </a>
                                <a class="listar-ativos-remover" id="listar-ativos-remover">
                                    <span class="btn btn-warning text-white py-1 px-2 rounded"><i
                                            class="mdi mdi-minus mdi-18px"></i></span>
                                </a>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="nome_servico">Nome do Serviço</label>
                            <input type="text" name="nome_servico[]" id="nome_servico" class="form-control form-control-sm" required>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="situacao" class="btn btn-warning btn-sm px-5 m-1 shadow-lg">Situação</label>
                            <select name="situacao[]" id="situacao" class="form-control form-control-sm"" required>
                                <option selected>Selecionar</option>
                                <option value="1">&#9899; - Obrigatória</option>
                                <option value="2">&#9673; - Executar conforme condição</option>
                                <option value="3">&#9650; - Conferir/ Verificar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="periodo">Período</label>
                            <input type="text" name="periodo[]" id="periodo" class="form-control form-control-sm"" required>
                        </div>
                    </div>
                    <div class="col-2">
                        <div class="form-group">
                            <label for="tipo">Tipo</label>
                            <select name="tipo[]" id="tipo" class="form-control form-control-sm"" required>
                                <option selected>Selecionar</option>
                                <option value="km">Horas</option>
                                <option value="hr">Quilometros</option>
                                <option value="tmp">Tempo</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div id="listar-ativos-linha"></div>

                <template id="listar-ativos-template">
                    <div class="row template-row mt-4">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="nome_servico">Nome do Serviço</label>
                                <input type="text" name="nome_servico[]" id="nome_servico" class="form-control form-control-sm"" required>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="situacao" class="btn btn-warning btn-sm m-1 px-5 shadow-lg">Situação</label>
                                <select name="situacao[]" id="situacao" class="form-control form-control-sm" required>
                                    <option selected>Selecionar</option>
                                    <option value="1">&#9899; - Obrigatória</option>
                                    <option value="2">&#9673; - Executar conforme condição</option>
                                    <option value="3">&#9650; - Conferir/ Verificar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="periodo">Período</label>
                                <input type="text" name="periodo[]" id="periodo" class="form-control form-control-sm"" required>
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="tipo">Tipo</label>
                                <select name="tipo[]" id="tipo" class="form-control form-control-sm"" required>
                                    <option selected>Selecionar</option>
                                    <option value="km">Horas</option>
                                    <option value="hr">Quilometros</option>
                                    <option value="tmp">Tempo</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </template>

                <button type="submit" class="btn btn-success mt-3">Salvar</button>
            </form>
        </div>
    </div>
@endsection
