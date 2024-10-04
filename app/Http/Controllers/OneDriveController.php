<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;

class OneDriveController extends Controller
{
    private $access_token;
    private $siteId;
    private $folderPath;

    public function __construct()
    {
        // Obter o token de acesso automaticamente
        $this->access_token = $this->getToken(); // Corrigido para usar $this

        if (!$this->access_token) {
            Log::info('Erro ao obter o token de acesso. Verifique as credenciais.');
            return;
        }

        // Certifique-se de que o siteId está correto
        $this->siteId = '4ca1f317-1e25-4523-ad97-3e244d17e63e';

        // Definir o caminho da pasta no SharePoint onde queremos criar a subpasta
        $this->folderPath = 'Engeativos/SGA';  // Caminho da pasta onde você deseja criar a nova pasta
    }

    public function getToken()
    {
        // Definindo as variáveis de autenticação dentro do método
        $tenantID = getenv('AZURE_TENANT_ID'); // O ID do seu tenant (diretório) no Azure AD
        $clientID = getenv('AZURE_CLIENT_ID'); // O ID do aplicativo que você registrou
        $clientSecret = getenv('AZURE_CLIENT_SECRET'); // O segredo gerado para a sua aplicação
        $scope = getenv('MS_GRAPH_SCOPE'); // Escopo para a API da Microsoft Graph
        $token_url = getenv('TOKEN_URL');
        $token_file = $token_file = storage_path('app/' . getenv('TOKEN_FILE'));  // Arquivo onde o token será armazenado

        // Verifica se o arquivo de token existe
        if (file_exists($token_file)) {

            $token_data = json_decode(file_get_contents($token_file), true);

            // Verifica se o token ainda é válido
            if (time() < $token_data['expires_at']) {
                return $token_data['access_token'];
            }
        }

        // Caso o token não exista ou tenha expirado, obtém um novo
        return $this->getNewToken($token_url, $clientID, $clientSecret, $scope);
    }

    // Função para obter o token de acesso
    public function getNewToken($token_url, $clientID, $clientSecret, $scope)
    {
        $data = [
            'grant_type' => 'client_credentials',
            'client_id' => $clientID,
            'client_secret' => $clientSecret,
            'scope' => $scope,
        ];

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $token_url);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($curl);
        $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($curl);
        curl_close($curl);

        // Verificação de sucesso da requisição
        if ($http_code == 200) {
            $response_data = json_decode($response, true);

            // Verificação adicional para garantir que o access_token foi obtido
            if ($response_data === null || !isset($response_data['access_token'])) {
                Log::info('Erro ao decodificar a resposta JSON ou token não encontrado.');
                Log::info('Resposta: ' . $response);
                return null;
            }

            // Adiciona o tempo de expiração e salva o token
            $response_data['expires_at'] = time() + $response_data['expires_in'];
            file_put_contents(storage_path('app/public/token/token.json'), json_encode($response_data));

            return $response_data['access_token'];
        } else {
            // Exibe informações de erro
            Log::info('Erro ao obter o token. Código HTTP: ' . $http_code);
            Log::info("Erro cURL: " . $curl_error);
            Log::info('Resposta da API: ' . $response);

            return null;
        }
    }

    public function index()
    {
        return view('ondrive.index');
    }

   
    // Função para fazer o upload do arquivo na pasta
    public function uploadFile(Request $request)
    {
        $folderName = $request->folder_name;
        $fileName = $request->file('file');

        if (empty($this->siteId)) {
            $notification = array(
                'title' => "Atenção!",
                'message' => "Erro: siteId não disponível. Verifique se o siteId foi obtido corretamente.",
                'type' => 'warning'
            );
            return back()->with($notification);
        }

        // Construir o caminho completo incluindo o nome do arquivo
        $path = $this->folderPath . '/' . $folderName . '/' . $fileName->getClientOriginalName();

        // Codificar os segmentos do caminho para lidar com caracteres especiais
        $pathSegments = array_map('rawurlencode', explode('/', $path));
        $encodedPath = implode('/', $pathSegments);

        // URL para upload do arquivo na pasta especificada
        $uploadUrl = 'https://graph.microsoft.com/v1.0/sites/' . $this->siteId . '/drive/root:/' . $encodedPath . ':/content';

        // Ler o conteúdo do arquivo
        $fileContent = file_get_contents($fileName);

        // Cabeçalhos para a requisição
        $headers = [
            'Authorization: Bearer ' . $this->access_token,
            'Content-Type: application/octet-stream'
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
            dd('Erro cURL: ' . curl_error($ch));
        }

        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code == 201 || $http_code == 200) {
            $notification = array(
                'title' => "Successo",
                'message' => "Arquivo enviado com sucesso: " . $fileName->getClientOriginalName(),
                'type' => 'success'
            );
            return back()->with($notification);
        } else {
            $notification = array(
                'title' => "Atenção!!!",
                'message' => "Erro ao enviar arquivo: " . $fileName->getClientOriginalName() . ". Código HTTP: $http_code<br>" . "Resposta: $response<br>",
                'type' => 'warning'
            );
            return back()->with($notification);
        }
    }
}
