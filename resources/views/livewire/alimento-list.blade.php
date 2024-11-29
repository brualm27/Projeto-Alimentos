<div>
    <h1>Alimentos</h1>

    {{-- Exibe mensagem caso não haja alimentos cadastrados --}}
    @if($alimentos->isEmpty())
        <p><strong>Não há alimentos cadastrados.</strong></p>
    @else
        <ul>
            {{-- Exibe a lista de alimentos --}}
            @foreach($alimentos as $alimento)
                <li>
                    <a href="{{ route('alimentos.show', $alimento->id) }}">
                        {{ $alimento->nome }}
                    </a>
                </li>
            @endforeach
        </ul>
    @endif

    {{-- Exemplo de formulário para adicionar alimentos --}}
    <div class="mt-4">
        <h3>Adicionar Novo Alimento</h3>
        <form wire:submit.prevent="addAlimento">
            <div class="form-group">
                <label for="nome">Nome do Alimento</label>
                <input type="text" id="nome" class="form-control" wire:model="nome" required>
            </div>
            <button type="submit" class="btn btn-primary mt-2">Adicionar</button>
        </form>
    </div>
</div>
