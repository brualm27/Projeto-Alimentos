<?php

namespace App\Http\Controllers;

use App\Models\Alimento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AlimentoController extends Controller
{
    
    public function index()
    {
        $alimentos = Alimento::all();
        return view('alimentos.index', compact('alimentos'));
    }

    public function show($id)
    {
        $alimento = Alimento::findOrFail($id);
        $foodName = $alimento->nome;
        Log::debug("Alimento encontrado: {$foodName}");

        $apiKey = env('OPENAI_API_KEY');
        if (!$apiKey) {
            abort(500, 'Chave da API não configurada');
        }

        $modelUrl = 'https://api.openai.com/v1/chat/completions';
        Log::debug("Enviando requisição para a API do OpenAI para o alimento: {$foodName}");

        $response = Http::withHeaders([
            'Authorization' => "Bearer $apiKey",
            'Content-Type' => 'application/json',
        ])->post($modelUrl, [
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                ['role' => 'system', 'content' => 'Você é um nutricionista experiente.'],
                ['role' => 'user', 'content' => "Quais são os valores nutricionais do alimento chamado '$foodName'?"],
            ],
            'max_tokens' => 200,
        ]);

        Log::debug('Resposta da API:', $response->json());

        if ($response->successful()) {
            $data = $response->json();
            $nutritionalInfo = $data['choices'][0]['message']['content'] ?? 'Informações nutricionais indisponíveis';

            $calorias = $this->extractCalorias($nutritionalInfo);
            $alimento->update([
                'calorias' => $calorias,
                'nutritional_info' => $nutritionalInfo,
            ]);

            Log::debug("Informações salvas para o alimento: {$foodName}");
        } else {
            Log::error("Erro ao obter informações nutricionais", [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);
            $nutritionalInfo = 'Não foi possível obter informações nutricionais';
        }

        return view('alimentos.show', compact('alimento', 'nutritionalInfo'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'calorias' => 'nullable|integer',
            'nutritional_info' => 'nullable|string',
        ]);

        Alimento::create($validatedData);

        return redirect()->route('alimentos.index')
                         ->with('success', 'Alimento criado com sucesso!');
    }

    private function extractCalorias($nutritionalInfo)
    {
        if (preg_match('/calorias?:?\s*(\d+)/i', $nutritionalInfo, $matches)) {
            return (int)$matches[1];
        }

        Log::warning("Calorias não encontradas no texto: {$nutritionalInfo}");
        return null;
    }
}
