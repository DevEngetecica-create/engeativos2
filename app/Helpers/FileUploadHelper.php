<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class FileUploadHelper
{
    // Método para obter ou renovar o token
    public static function getToken()
    {
        $tenantID = getenv('AZURE_TENANT_ID');
        $clientID = getenv('AZURE_CLIENT_ID');
        $clientSecret = getenv('AZURE_CLIENT_SECRET');
        $scope = getenv('MS_GRAPH_SCOPE');
        $token_url = getenv('TOKEN_URL');
        $token_file = storage_path(getenv('TOKEN_FILE'));

        // Verifica se o arquivo de token existe
        if (file_exists($token_file)) {
            $token_data = json_decode(file_get_contents($token_file), true);

            // Verifica se o token ainda é válido
            if (time() < $token_data['expires_at']) {
                return $token_data['access_token'];
            }
        }

        // Caso o token não exista ou tenha expirado, obtém um novo
        return self::getNewToken($token_url, $clientID, $clientSecret, $scope);
    }

    // Método para obter um novo token
    public static function getNewToken($token_url, $clientID, $clientSecret, $scope)
    {
        $data = [
            'grant_type' => 'client_credentials',
            'client_id' => $clientID,
            'client_secret' => $clientSecret,
            'scope' => $scope,
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $token_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        curl_close($curl);

        $response_data = json_decode($response, true);

        if (isset($response_data['access_token'])) {
            $response_data['expires_at'] = time() + $response_data['expires_in'];
            file_put_contents(storage_path(getenv('TOKEN_FILE')), json_encode($response_data));
            return $response_data['access_token'];
        }

        Log::error('Erro ao obter o token de autenticação');
        throw new \Exception("Erro ao obter o token");
    }

    // Função de upload de arquivos para a pasta
    public static function uploadFilesToFolder($arquivos, $caminho_arquivo)
    {
        $accessToken = self::getToken();  // Agora o token é obtido diretamente aqui

        if (empty($siteId)) {
            return ['status' => 'error', 'message' => 'Erro: siteId não disponível. Verifique se o siteId foi obtido corretamente.'];
        }

        // Se receber um único arquivo, transforme em array para simplificar o tratamento
        if (!is_array($arquivos)) {
            $files = [$arquivos];
        }

        // Iterar sobre os arquivos
        $responses = [];
        foreach ($files as $file) {
            // Construir o caminho completo incluindo o nome do arquivo
            $path = getenv('PATH_WORKSPACE') . $caminho_arquivo . '/' . $file->getClientOriginalName();

            // Codificar os segmentos do caminho para lidar com caracteres especiais
            $pathSegments = array_map('rawurlencode', explode('/', $path));
            $encodedPath = implode('/', $pathSegments);

            // URL para upload do arquivo na pasta especificada
            $uploadUrl = 'https://graph.microsoft.com/v1.0/sites/' . getenv('SITE_ID') . '/drive/root:/' . $encodedPath . ':/content';

            // Ler o conteúdo do arquivo
            $fileContent = file_get_contents($file);

            // Cabeçalhos para a requisição
            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/octet-stream',
            ];

            // Inicializar cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $uploadUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fileContent);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            // Executar a requisição e capturar a resposta
            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                $responses[] = [
                    'status' => 'error',
                    'message' => 'Erro cURL: ' . curl_error($ch),
                    'file' => $file->getClientOriginalName()
                ];
                continue;
            }

            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($http_code == 201 || $http_code == 200) {
                $responses[] = [
                    'status' => 'success',
                    'message' => 'Arquivo enviado com sucesso: ' . $file->getClientOriginalName(),
                ];
            } else {
                $responses[] = [
                    'status' => 'error',
                    'message' => "Erro ao enviar arquivo: " . $file->getClientOriginalName() . ". Código HTTP: $http_code<br>Resposta: $response<br>",
                ];
            }
        }

        return $responses;
    }
}
