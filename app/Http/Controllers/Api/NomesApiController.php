<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class NomesApiController extends Controller
{
    public function index() { //RETORNA UM TOP 10 NOMES MAIS USADOS NO BRASIL
        
        //REQUEST PARA API DO IBGE
        $response = Http::get('https://servicodados.ibge.gov.br/api/v2/censos/nomes/');
        
        
        if ($response->successful()) {
            
            if (empty($response->json())) { //CASO RETORNE VAZIO
                return response()->json(['vazio' => 'dados não encontrados'], 404);
            }
            
            //RETORNA APENAS 10 ELEMENTOS DA RESPONSE
            $top10 = array_slice($response->json(), 0, 10);

            return response()->json($top10, 200);


        } elseif($response->status() === 404) {
            
            return response()->json(['erro' => 'recurso não encontrado'], 404);

        } else {
            return response()->json(['erro' => 'Erro interno na api'], 500);
        } 

        
    }

    public function nome() {
        $nome = request('nome'); //OBTENDO O PARÂMETRO DA URL

        if (empty($nome)) { //CASO O PARÂMETRO NOME ESTEJA VAZIO
            return response()->json(['vazio' => 'O parâmetro nome está vazio e é obrigatório'], 400);
        }

        $response = Http::get('https://servicodados.ibge.gov.br/api/v2/censos/nomes/'. $nome);

        if ($response->successful()) { //SE A REQUEST OCORRER TUDO BEM
        
            if (empty($response->json())) { //verifica se a response retornou vazia
                return response()->json(['erro' => 'não foram encontrados resultados para este nome'], 404);
            }

            return response()->json($response->json(), 200); //retorna dados normalmente da response

        } elseif ($response->status() > 400) {
         
            return  response()->json(['erro' => 'recurso indisponível'], $response->status());
        } else {

            return response()->json(['erro' => 'erro interno no servidor'], 500);
        }        
        

    }

    public function decada_mais_frequente() { //FUNÇÃO PARA SABER QUAL DÉCADA O NOME FOI MAIS USADO        
        
        $nome = request('nome'); //OBTENDO O PARÂMETRO DA URL

        if(empty($nome)) {
            return response()->json(['vazio' => 'Parâmetro nome está vazio e é obrigatório!'], 400);
        }


        $response = Http::get('https://servicodados.ibge.gov.br/api/v2/censos/nomes/'. $nome);
        

        if($response->successful()) {

            if (empty($response->json())) { //CASO O NOME INSERIDO NÃO RETORNE RESULTADOS
                return response()->json(['erro' => 'não foram encontrados resultados para este nome'], 404);
            }

            $maiorFrequencia = 0;
            $decada = '';
            
            foreach ($response->json() as $item) {
                foreach($item['res'] as $valor) { //PERCORRE TODOS OS PERÍODOS E FREQUÊNCIAS
                    
                    if($maiorFrequencia < $valor['frequencia']) {  //ATRIBUI A MAIOR FREQUÊNCIA PARA A VARIÁVEL, CASO OCORRA A CONDIÇÃO
                    
                        $maiorFrequencia = $valor['frequencia'];
                        $decada = $valor['periodo'];
                    }
                }
            }
            
            $resultado = ['decada' => $decada, 'freq' => $maiorFrequencia];
            return response()->json($resultado, 200); //RETORNA A DÉCADA COM MAIOR FREQUÊNCIA DO USO DO NOME.

        } elseif($response->status() > 400) {
            
            return response()->json(['erro' => 'recurso indisponível ou não encontrado'], $response->status());
        } else {
            
            return response()->json(['erro' => 'erro interno no servidor'], 500);
        }        
    }

}
