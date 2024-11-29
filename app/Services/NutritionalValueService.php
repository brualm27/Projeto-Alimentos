<?php

namespace App\Services;

use OpenAI\Client;

class NutritionalValueService
{
    protected $client;
    protected $apiKey;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->apiKey = env('OPENAI_API_KEY');
    }

    public function getNutritionalValues($foodName)
    {
        
        $prompt = "Forneça os valores nutricionais para o alimento: {$foodName}.";

       
        $model = 'gpt-4';

        try {
            $response = $this->client->post('https://api.openai.com/v1/completions', [
                'headers' => [
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ],
                'json' => [
                    'model' => $model,
                    'prompt' => $prompt,
                    'max_tokens' => 150,
                    'temperature' => 0.7,
                ]
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);
            return $responseData['choices'][0]['text'] ?? 'Informações nutricionais não encontradas';
        } catch (\Exception $e) {
            return 'Erro ao acessar a API: ' . $e->getMessage();
        }
    }
}
