<?php

namespace App\Http\Controllers;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class NutritionPlanController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idade' => 'required|integer|min:1',
            'sexo' => 'required|in:masculino,feminino',
            'peso' => 'required|numeric|min:1',
            'altura' => 'required|numeric|min:1',
            'objetivo' => 'required|in:manter,perder,ganhar',
            'alimento_favorito_1' => 'nullable|string|max:255',
            'alimento_favorito_2' => 'nullable|string|max:255',
            'alimento_favorito_3' => 'nullable|string|max:255',
        ]);

        $idade = $validated['idade'];
        $sexo = $validated['sexo'];
        $peso = $validated['peso'];
        $altura = $validated['altura'];
        $objetivo = $validated['objetivo'];

        $alimentos_favoritos = array_filter([
            $validated['alimento_favorito_1'] ?? null,
            $validated['alimento_favorito_2'] ?? null,
            $validated['alimento_favorito_3'] ?? null,
        ]);

        $alimentos_favoritos_html = array_map(function ($alimento) {
            return '<strong>' . $alimento . '</strong>';
        }, $alimentos_favoritos);

        
        if ($sexo == 'masculino') {
            $bmr = 88.362 + (13.397 * $peso) + (4.799 * $altura) - (5.677 * $idade);
        } else {
            $bmr = 447.593 + (9.247 * $peso) + (3.098 * $altura) - (4.330 * $idade);
        }

        switch ($objetivo) {
            case 'manter':
                $calorias = $bmr * 1.55; 
                break;
            case 'perder':
                $calorias = $bmr * 1.2; 
                break;
            case 'ganhar':
                $calorias = $bmr * 1.75; 
                break;
            default:
                $calorias = $bmr; 
                break;
        }

        
        $prompt = "
        Crie um plano alimentar semanal para uma pessoa com as seguintes características:
        - Idade: {$idade}
        - Sexo: {$sexo}
        - Peso: {$peso} kg
        - Altura: {$altura} cm
        - Objetivo: {$objetivo}
        - Alimentos favoritos: " . implode(", ", $alimentos_favoritos_html) . "
        
        O plano deve incluir refeições variadas ao longo da semana e distribuir os alimentos favoritos de forma equilibrada. O valor calórico recomendado é de " . round($calorias, 2) . " calorias por dia.
        ";

       
        $client = new Client();

        try {
         
            $response = $client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => 'gpt-3.5-turbo',  
                    'messages' => [
                        ['role' => 'system', 'content' => 'Você é um assistente útil.'],
                        ['role' => 'user', 'content' => $prompt],
                    ],
                    'max_tokens' => 1500,
                    'temperature' => 0.7,
                ]
            ]);

         
            $responseData = json_decode($response->getBody()->getContents(), true);
            $resultado = $responseData['choices'][0]['message']['content'];

            $cardapio_semanal = $this->processarPlanoAlimentar($resultado);

          
            $descricao = "O valor calórico recomendado foi calculado com base no seu metabolismo basal (BMR) "
                       . "e o seu objetivo. O BMR é ajustado conforme o seu nível de atividade e objetivo "
                       . "de manutenção, perda ou ganho de peso.";

          
            $cardapio = [
                'idade' => $idade,
                'sexo' => $sexo,
                'peso' => $peso,
                'altura' => $altura,
                'objetivo' => $objetivo,
                'calorias' => round($calorias, 2), 
                'descricao' => $descricao,
                'cardapio_semanal' => $cardapio_semanal,
            ];

          
            return view('nutrition.plan', compact('cardapio'));

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao acessar a API da OpenAI: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Função para processar o texto da resposta da API e transformá-lo em um array associativo.
     *
     * @param string $resultado
     * @return array
     */
    private function processarPlanoAlimentar($resultado)
    {
        $cardapio = [];
        $dias = explode("\n", $resultado); 
    
        $dia = null;  
    
        foreach ($dias as $linha) {
            
            $linha = trim($linha);
            
          
            if (preg_match('/(segunda-feira|terça-feira|quarta-feira|quinta-feira|sexta-feira|sábado|domingo)/i', $linha, $matches)) {
               
                $dia = $matches[0];
                $cardapio[$dia] = [
                    'refeicoes' => [] 
                ];
            } elseif ($dia && !empty($linha)) {
               
                $cardapio[$dia]['refeicoes'][] = $linha;
            }
        }
    
        if (isset($cardapio['domingo']) && !empty($cardapio['domingo']['refeicoes'])) {
            $refeicoes_domingo = $cardapio['domingo']['refeicoes'];
            
            
            array_pop($refeicoes_domingo);
            
            $cardapio['domingo']['refeicoes'] = $refeicoes_domingo;
        }
    
        foreach ($cardapio as $dia => $dados) {
            if (empty($dados['refeicoes'])) {
                unset($cardapio[$dia]);
            }
        }
    
        return $cardapio;
    }
    

}
