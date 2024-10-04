<div class="row">
    <div class="col-md-12 grid-margin stretch-card">
        <div class="card">
            <div class="card-body text-justify">

                <h4 class="card-title">DADOS DO FUNCIONÁRIO</h4>
                <table class="table table-bordered table-striped table-houver">
                    <thead class="table-dark">
                        <tr>
                            <th>Requisitante</th>
                            <th>Matrícula</th>
                            <th>Obra Atual</th>
                            <th>Usuário Autorizado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $detalhes->funcionario }}</td>
                            <td>{{ $detalhes->funcionario_matricula }}</td>
                            <td>{{ $detalhes->codigo_obra }}</td>
                            <td>{{ $detalhes->name }}</td>
                        </tr>
                    </tbody>
                </table>


                <hr>
                <h4 class="card-title">TERMO DE RETIRADA</h4>
                <p class="card-description">Identificação da Retirada #{{ $detalhes->id }}</p>
                                       <p>
                        PROPRIETÁRIO: ENGETECNICA ENGENHARIA E CONSTRUÇÃO LTDA, inscrita no CNPJ: 76.624.584/0001-38, 
                        Rua do Semeador, 431, CEP 81270-050, Cidade Industrial, Município de Curitiba,
                        Estado do Paraná, doravante denominada COMODANTE. 
                        </p>

                        <p>
                        USUÁRIO: Contratado pela Engetecnica Engenharia e Construção Ltda, doravante denominada COMODATÁRIO. 
                        </p>

                        <hr>
                        <h4 class="card-title">CLÁUSULA PRIMEIRA – DAS DECLARAÇÕES</h4>

                        <p>1.1 Declaro ter recebido da COMODANTE, à título de empréstimo, para uso em minhas funções operacionais, 
                        conforme determinado em lei, as ferramentas e equipamentos especificados neste termo de responsabilidade. </p>

                        <p>1.2 O COMODATÁRIO compromete-se a zelar, cuidar, manter em ordem e perfeito funcionamento, todas as 
                            ferramentas e equipamentos disponibilizados para uso pela COMODANTE, durante todo período 
                            da execução de suas atividades. </p>

                        <hr>
                        <h4 class="card-title">CLÁUSULA SEGUNDA – DAS OBRIGAÇÕES E RESPONSABILIDADES</h4>

                        <p>O COMODATÁRIO será responsabilizado em caso de danificar, extraviar, emprego inadequado, e/ou mau uso.
                            A COMODANTE fornecerá um novo equipamento ao COMODATÁRIO e cobrará o valor de acordo com o custo do 
                            equipamento da mesma marca e modelo ou equivalente, disponível no mercado. </p>


                        <p>2.2 Em caso de dano e inutilização do equipamento por parte do COMODATÁRIO o mesmo, deverá comunicar
                            por escrito a COMODANTE apresentando o equipamento danificado no prazo máximo de 24 horas para 
                            equipe de ativos da COMODANTE </p>

                        <p>2.3 Em caso de furto ou roubo, o COMODATÁRIO deverá: (I) comunicar imediatamente a COMODANTE e, 
                            (II) apresentar o boletim de ocorrência, no qual informe detalhadamente os fatos e as 
                            circunstâncias do ocorrido. </p>

                        <p>2.4 Uma vez em posse do COMODATÁRIO ferramentas e equipamentos, a COMODANTE poderá a qualquer
                             momento e sem prévio aviso, realizar as inspeções e conferências de todos os itens 
                             disponibilizados ao COMODATÁRIO. </p>

                        <hr>
                        <h4 class="card-title">CLÁUSULA TERCEIRA – DOS ITENS DISPONIBILIZADOS</h4>

                        <p>3.1 Todos os itens da lista abaixo foram conferidos pela COMODANTE e COMODATARIO, tendo sido 
                            testado(s) e recebido(s) pelo COMODATÁRIO sem qualquer defeito e em pleno funcionamento, 
                            atendendo a todos os requisitos de segurança aplicáveis aos mesmos. Ítens Retirados </p>
                        <hr>

                <hr>
                <h4 class="card-title">Ítens Retirados</h4>
                <table class="table table-bordered table-striped table-houver">
                    <thead>
                        <tr>
                            <th> Obra </th>
                            <th> Solicitante </th>
                            <th> Requisitante </th>
                            <th> Item </th>
                            <th> Data de Inclusão </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($detalhes->itens as $item)
                        <tr>
                            <td>{{ $detalhes->codigo_obra }}</td>
                            <td>{{ $detalhes->name }}</td>
                            <td>{{ $detalhes->funcionario }}</td>
                            <td>
                                <div class="badge badge-danger">{{ $item->item_codigo_patrimonio }}
                                </div>
                                <div class="badge badge-info">{{ $item->item_nome }}</div>
                            </td>
                            <td>{{ Tratamento::FormatarData($detalhes->created_at) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <p>Data para devolução: <b>{{ Tratamento::FormatarData($detalhes->data_devolucao_prevista) }}</b></p>


                <hr>
                <h4 class="card-title">Assinaturas</h4>

                <table class="table table-bordered">
                    <tr>
                        <td width="50%"><b>COMODANTE</b></td>
                        <td width="50%"><b>COMODATÁRIO</b></td>
                    </tr>
                    <tr>
                        <td>ENGETECNICA ENGENHARIA E CONSTRUCAO LTDA</td>
                        <td><b>Nome:</b> {{ $detalhes->funcionario }}</td>
                    </tr>
                    <tr>
                        <td><b>Representante:</b> {{ $detalhes->name }}</td>
                        <td><b>Matrícula:</b> {{ $detalhes->funcionario_matricula }}</td>
                    </tr>
                </table>

                <p><b>Este termo foi gerado através da Plataforma SGA-E</b></p>
                <p>{{ date('d/m/Y H:i:s') }}</p>

            </div>
        </div>
    </div>
</div>