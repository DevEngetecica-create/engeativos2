<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class FipeController extends Controller
{
    public function consultarMarcas(Request $request)
    {
        $client = new Client([
            'verify' => false // Inserir o caminho do certificado.cert caso venha ser obrigatório
        ]);

        $url = "https://veiculos.fipe.org.br/api/veiculos//ConsultarMarcas";
      
        /*  exemplo de parametros a serem passados para as API
            codigoTabelaReferencia: 311
            codigoMarca: 103
            codigoModelo: 3131
            codigoTipoVeiculo: 3
            anoModelo: 1989
            codigoTipoCombustivel: 3
            tipoVeiculo: caminhao
            modeloCodigoExterno: 
            tipoConsulta: tradicional
        */

        try {
            $response = $client->post($url, [
                'json' => [
                    'codigoTabelaReferencia'    => $request->input('codigoTabelaReferencia'),
                    'codigoTipoVeiculo'         => $request->input('codigoTipoVeiculo')
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $content = json_decode($response->getBody(), true);

            if ($statusCode == 200) {
                return response()->json($content);
            } else {
                return response()->json(['error' => 'Erro ao consultar marcas'], $statusCode);
            }

        } catch (\Exception $e) {

            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



    public function consultarModelos(Request $request)
    {
        $client = new Client([
            'verify' => false // Inserir o caminho do certificado.cert caso venha ser obrigatório
        ]);

        $url = "https://veiculos.fipe.org.br/api/veiculos//ConsultarModelos";      

        try {
            $response = $client->post($url, [
                'json' => [
                    'codigoTabelaReferencia'    => $request->input('codigoTabelaReferencia'),
                    'codigoTipoVeiculo'         => $request->input('codigoTipoVeiculo'),
                    'codigoMarca'               => $request->input('codigoMarca')
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $content = json_decode($response->getBody(), true);

            if ($statusCode == 200) {
                return response()->json($content);
            } else {
                return response()->json(['error' => 'Erro ao consultar marcas'], $statusCode);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function consultarAnoModelos(Request $request)
    {
        $client = new Client([
            'verify' => false // Inserir o caminho do certificado.cert caso venha ser obrigatório
        ]);

        $url = "https://veiculos.fipe.org.br/api/veiculos//ConsultarAnoModelo";      

        try {
            $response = $client->post($url, [
                'json' => [
                    'codigoTabelaReferencia'    => $request->input('codigoTabelaReferencia'),
                    'codigoTipoVeiculo'         => $request->input('codigoTipoVeiculo'),
                    'codigoMarca'               => $request->input('codigoMarca'),
                    'codigoModelo'              => $request->input('codigoModelo')
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $content = json_decode($response->getBody(), true);

            if ($statusCode == 200) {
                return response()->json($content);
            } else {
                return response()->json(['error' => 'Erro ao consultar marcas'], $statusCode);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function ConsultarModelosAtravesDoAno(Request $request)
    {
        $client = new Client([
            'verify' => false // Inserir o caminho do certificado.cert caso venha ser obrigatório
        ]);

        $url = "https://veiculos.fipe.org.br/api/veiculos//ConsultarModelosAtravesDoAno";      

        try {
            $response = $client->post($url, [
                'json' => [
                    'codigoTabelaReferencia'    => $request->input('codigoTabelaReferencia'),
                    'codigoTipoVeiculo'         => $request->input('codigoTipoVeiculo'),
                    'codigoMarca'               => $request->input('codigoMarca'),
                    'codigoModelo'              => $request->input('codigoModelo'),
                    'ano'                       => $request->input('ano'),
                    'codigoTipoCombustivel'     => 1,
                    'anoModelo'                 => 1995
                ],
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $content = json_decode($response->getBody(), true);

            if ($statusCode == 200) {
                return response()->json($content);
            } else {
                return response()->json(['error' => 'Erro ao consultar marcas'], $statusCode);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    
    public function ConsultarValorComTodosParametros(Request $request)
    {
        $client = new Client([
            'verify' => false // Inserir o caminho do certificado.cert caso venha ser obrigatório
        ]);

        $url = "https://veiculos.fipe.org.br/api/veiculos//ConsultarValorComTodosParametros";      

        try {
            $response = $client->post($url, [
                'json' => [
                    'codigoTabelaReferencia'    => $request->input('codigoTabelaReferencia'),
                    'codigoTipoVeiculo'         => $request->input('codigoTipoVeiculo'),
                    'codigoMarca'               => $request->input('codigoMarca'),
                    'codigoModelo'              => $request->input('codigoModelo'),
                    'ano'                       => $request->input('ano'),
                    'codigoTipoCombustivel'     => $request->input('codigoTipoCombustivel'),
                    'anoModelo'                 => $request->input('anoModelo'),
                    'tipoVeiculo'               => $request->input('tipoVeiculo'),                  
                    'tipoConsulta'              => $request->input('tipoConsulta')
                ],
                
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);

            $statusCode = $response->getStatusCode();
            $content = json_decode($response->getBody(), true);

            if ($statusCode == 200) {
                return response()->json($content);
            } else {
                return response()->json(['error' => 'Erro ao consultar marcas'], $statusCode);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
