<?php

namespace App\Services;

class OneDriveAPI
{
    private $access_token;
    private $siteId;
    private $listId;
    private $folderPath;

    public function __construct($access_token)
    {
        $this->access_token = $access_token;

        // Definir o siteId e listId da sua biblioteca no SharePoint
        $this->siteId = '4ca1f317-1e25-4523-ad97-3e244d17e63e'; // Seu siteId
        $this->listId = 'da2b6422-7887-4792-976b-8c3a903f3871'; // Seu listId (biblioteca de documentos)

        // Definir o caminho da pasta no SharePoint (Exemplo: "Engeativos/fotos_veiculos")
        $this->folderPath = 'Engeativos';  // Ajuste o caminho conforme necessário
    }

    // Função para fazer upload do arquivo para o SharePoint/OneDrive
    public function uploadToOneDrive($fileName, $fileContent)
    {
        // URL para enviar o arquivo para a biblioteca do SharePoint usando o siteId e listId
        $uploadUrl = 'https://graph.microsoft.com/v1.0/sites/' . $this->siteId . '/lists/' . $this->listId . '/drive/root:/' . $this->folderPath . '/' . $fileName . ':/content';

        // Cabeçalhos da requisição
        $headers = [
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/octet-stream'  // Tipo de conteúdo binário
        ];

        // Inicializar cURL
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $uploadUrl);
        curl_setopt($curl, CURLOPT_PUT, 1);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $fileContent);

        // Executar a requisição e capturar a resposta
        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE); // Captura o código de resposta HTTP
        $curl_error = curl_error($curl); // Captura erros de cURL, se houver
        curl_close($curl);

        // Verificar se o upload foi bem-sucedido e exibir resposta para debug
        if ($http_code == 201) {
            return true;
        } else {
            // Exibir erro detalhado para debug
            echo "Erro ao fazer upload da imagem: $fileName. Código HTTP: $http_code <br>";
            echo "Resposta completa: $response <br>";
            if (!empty($curl_error)) {
                echo "Erro cURL: $curl_error <br>";
            }
            return false;
        }
    }
}