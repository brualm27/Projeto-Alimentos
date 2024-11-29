@extends('adminlte::page')

@section('title', 'Cardápio Nutricional')

@section('content_header')
    <h1>Cardápio Nutricional</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Preencha suas informações para gerar o cardápio nutricional</h3>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('nutrition.plan') }}">
                @csrf
                <div class="form-group">
                    <label for="idade">Idade (em anos)</label>
                    <input type="number" class="form-control" id="idade" name="idade" required>
                </div>

                <div class="form-group">
                    <label for="sexo">Sexo</label>
                    <select class="form-control" id="sexo" name="sexo" required>
                        <option value="masculino">Masculino</option>
                        <option value="feminino">Feminino</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="peso">Peso (kg)</label>
                    <input type="number" class="form-control" id="peso" name="peso" step="0.1" required>
                </div>

                <div class="form-group">
                    <label for="altura">Altura (cm)</label>
                    <input type="number" class="form-control" id="altura" name="altura" step="0.1" required>
                </div>

                <div class="form-group">
                    <label for="objetivo">Objetivo</label>
                    <select class="form-control" id="objetivo" name="objetivo" required>
                        <option value="manter">Manter peso</option>
                        <option value="perder">Perder peso</option>
                        <option value="ganhar">Ganhar peso</option>
                    </select>
                </div>

                <div class="form-group">
    <label for="alimento_favorito_1">Alimento Favorito 1</label>
    <input type="text" class="form-control" id="alimento_favorito_1" name="alimento_favorito_1" placeholder="Exemplo: Maçã">
</div>

<div class="form-group">
    <label for="alimento_favorito_2">Alimento Favorito 2</label>
    <input type="text" class="form-control" id="alimento_favorito_2" name="alimento_favorito_2" placeholder="Exemplo: Banana">
</div>

<div class="form-group">
    <label for="alimento_favorito_3">Alimento Favorito 3</label>
    <input type="text" class="form-control" id="alimento_favorito_3" name="alimento_favorito_3" placeholder="Exemplo: Frango">
</div>


                <button type="submit" class="btn btn-primary">Gerar Cardápio Nutricional</button>
            </form>
        </div>
    </div>
@stop
