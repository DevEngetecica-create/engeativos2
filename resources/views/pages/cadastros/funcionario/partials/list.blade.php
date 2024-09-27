<div class="card">
    <div class="card-body p-3">
        <div class="table-responsive">
            <table class="table table-bordered table-hover table-sm align-middle table-nowrap mb-0">
                <thead>
                    <tr>
                        <th class="text-center" width="8%">ID</th>
                        <th>Obra</th>
                        <th>Matrícula</th>
                        <th>Nome Completo</th>
                        <th>Função</th>
                        <th>Documentos</th>
                        <th>Setor</th>
                        <th>WhatsApp</th>
                        <th>E-mail</th>
                        <th>Status</th>
                        <th class="text-center {{ session()->get('usuario_vinculo')->id_nivel <= 2 or (session()->get('usuario_vinculo')->id_nivel == 14 ? 'd-block' : 'd-none') }}"
                            width="13%">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lista as $v)
                        <tr>
                            <td class="text-center">{{ $v->id }}</span></td>
                            <td>{{ $v->obra->codigo_obra ?? 'Obra desativada' }}</td>
                            <td>{{ $v->matricula ?? '-' }}</td>
                            <td class="text-uppercase">{{ $v->nome }}                               
                           

                            @if ($v->funcao && $v->funcao->funcao)
                                <td>
                                    <p class="text-capital">{{ $v->funcao->funcao }}</p>
                                </td>
                            @else
                                <td class="text-danger">Falta cadastrar a função</td>
                            @endif

                           
                            <td>
                                @php
                                $contar_situacao_1 = $v->qualificacoes->where('situacao')->count();
                                $contar_doc = $v->anexo_funcionarios->where('id_funcionario')->count();
                                $contar_dor_pendentes = $contar_situacao_1 - $contar_doc;

                                @endphp

                                @if ($contar_situacao_1)
                                    {{ $contar_situacao_1 }} doc's, desses faltam {{$contar_dor_pendentes}}
                                @else
                                @endif
                            </td>


                            <td>
                                @if($v->setor && $v->setor->nome_setor)

                                    {{$v->setor->nome_setor }}

                                    @else

                                    <span class="text-center text-danger">-- Sem reg. --</span>
                                @endif

                            </td>
                            <td>{{ $v->celular }}</td>
                            <td>{{ $v->email }}</td>
                            <td>{{ $v->status }} </td>

                            <td
                                class="d-flex text-center {{ session()->get('usuario_vinculo')->id_nivel <= 2 or (session()->get('usuario_vinculo')->id_nivel == 14 ? 'd-block' : 'd-none') }}">

                                <a class="btn btn-warning  btn-sm mr-2"
                                    href="{{ route('cadastro.funcionario.editar', $v->id) }}" title="Editar">
                                    Editar
                                </a>

                                <a class="btn btn-info btn-sm mx-2"
                                    href="{{ route('cadastro.funcionario.show', $v->id) }}" title="Visualizar">
                                    Ver
                                </a>

                                @if (session()->get('usuario_vinculo')->id_nivel == 1 or
                                        session()->get('usuario_vinculo')->id_nivel == 15 or
                                        session()->get('usuario_vinculo')->id_nivel == 10)
                                    <form action="{{ route('cadastro.funcionario.destroy', $v->id) }}" method="POST">
                                        @csrf
                                        @method('delete')
                                        <button class="btn btn-danger btn-sm" data-toggle="tooltip" data-placement="top"
                                            type="submit" title="Excluir"
                                            onclick="return confirm('Tem certeza que deseja excluir o registro?')">
                                            Ecluir
                                        </button>
                                    </form>
                                @endif

                                <!-- Botão para gerar a etiqueta -->
                                <a class="btn btn-success btn-sm mx-2" id="etiqueta_funcionario"
                                    data-id="{{ $v->id }}" data-bs-toggle="modal"
                                    data-bs-target="#modal_funcionario"
                                    href="{{ route('cadastro.funcionario.show', $v->id) }}" title="Imprimir etiqueta">
                                    Etiqueta
                                </a>

                                <!-- Botão para gerar o cracha -->
                                <span class="btn btn-warning btn-sm" id="cracha_funcionario"
                                    data-id="{{ $v->id }}" data-image="{{ $v->imagem_usuario }}"
                                    data-bs-toggle="modal" data-bs-target="#modal_cracha" title="Gerar cracha"> Crachá
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer clearfix">
        <div class="d-flex justify-content-end col-sm-12 col-md-12 col-lg-12 ">
            <div class="paginacao mx-3">
                {{ $lista->onEachSide(2)->links() }}
            </div>
        </div>
    </div>

</div>

<script>
    $(document).ready(function() {


        // Iterar sobre cada célula de função e aplicar a capitalização
        $('td .text-capital').each(function() {
            const funcao = $(this).text();
            const capitalizedFuncao = capitalizeProperly(funcao);
            $(this).text(capitalizedFuncao);
        });

        // Função para capitalizar as palavras, exceto preposições
        function capitalizeProperly(string) {
            // Lista de preposições que não devem ser capitalizadas
            const exceptions = ['da', 'de', 'do', 'das', 'dos', 'e'];

            // Converter tudo para minúsculas primeiro
            string = string.toLowerCase();

            // Dividir a string em palavras
            const words = string.split(' ');

            // Capitalizar cada palavra, exceto preposições
            const capitalizedWords = words.map((word, index) => {
                if (exceptions.includes(word) && index !== 0) {
                    return word; // Retorna a preposição em minúsculo se não for a primeira palavra
                } else {
                    return word.charAt(0).toUpperCase() + word.slice(1); // Capitaliza a primeira letra
                }
            });

            // Rejuntar as palavras em uma string
            return capitalizedWords.join(' ');
        }
    });
</script>
