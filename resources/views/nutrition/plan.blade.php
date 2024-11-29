@extends('adminlte::page')

@section('title', 'Plano Alimentar')

@section('content_header')
    <h1>Plano Alimentar</h1>
@stop

@section('content')
    <div class="card shadow-lg rounded-lg">
        <div class="card-header bg-primary text-white">
            <h3 class="card-title">Detalhes do Plano Alimentar</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Idade:</strong> {{ $cardapio['idade'] }}</p>
                    <p><strong>Sexo:</strong> {{ $cardapio['sexo'] }}</p>
                    <p><strong>Peso:</strong> {{ $cardapio['peso'] }} kg</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Altura:</strong> {{ $cardapio['altura'] }} cm</p>
                    <p><strong>Objetivo:</strong> {{ ucfirst($cardapio['objetivo']) }}</p>
                    <p><strong>Calorias por dia:</strong> {{ $cardapio['calorias'] }} kcal</p>
                </div>
            </div>
            <p><strong>Descrição:</strong> {{ $cardapio['descricao'] }}</p>
        </div>
    </div>

    <div class="card mt-4 shadow-lg rounded-lg">
        <div class="card-header bg-success text-white">
            <h3 class="card-title">Plano Semanal</h3>
        </div>
        <div class="card-body">
            <div class="row">
                @foreach($cardapio['cardapio_semanal'] as $dia => $dados)
                    <div class="col-md-4 mb-4">
                        <div class="card shadow-sm rounded-lg">
                            <div class="card-header bg-info text-white">
                                <h4 class="m-0">{{ ucfirst($dia) }}</h4>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled">
                                    @foreach($dados['refeicoes'] as $index => $refeicao)
                                        <li style="list-style-type: disc; margin-bottom: 5px;">
                                            <i class="fas fa-utensils"></i> {{ $refeicao }}
                                        </li>
                                    @endforeach
                                </ul>
                                
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .card {
            border-radius: 10px;
            border: none;
            margin-bottom: 20px;
        }
        .card-header {
            padding: 15px 20px;
        }
        .card-body {
            padding: 20px;
        }
        .list-unstyled li {
            font-size: 1.1rem;
        }
        .card-body ul li {
            display: flex;
            align-items: center;
        }
        .card-body ul li i {
            margin-right: 10px;
        }
        .shadow-lg {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .shadow-sm {
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .bg-primary {
            background-color: #007bff !important;
        }
        .bg-info {
            background-color: #17a2b8 !important;
        }
        .bg-success {
            background-color: #28a745 !important;
        }
    </style>
@stop
