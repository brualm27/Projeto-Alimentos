<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Alimento;

class AlimentoList extends Component
{
    public $alimentos;
    public $nome;


    public function mount()
    {
        $this->loadAlimentos();
    }

    // Método para carregar os alimentos do banco de dados
    public function loadAlimentos()
    {
        $this->alimentos = Alimento::all(); // Busca todos os alimentos
    }

    // Método para adicionar um novo alimento
    public function addAlimento()
    {
        // Validação simples
        $this->validate([
            'nome' => 'required|string|max:255',
        ]);

        // Criação do novo alimento
        Alimento::create([
            'nome' => $this->nome,
        ]);

        // Limpa o campo de nome após adicionar
        $this->nome = '';

        // Recarrega a lista de alimentos
        $this->loadAlimentos();
    }

    public function render()
    {
        return view('livewire.alimento-list', [
            'alimentos' => $this->alimentos,
        ]);
    }
}
