<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlimentoController;
use App\Http\Controllers\NutritionPlanController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/alimentos', [AlimentoController::class, 'index']);
Route::post('/nutrition/plan', [NutritionPlanController::class, 'store'])->name('nutrition.plan');
Route::get('/alimentos/{id}', [AlimentoController::class, 'show'])->name('alimentos.show');
Route::resource('alimentos', AlimentoController::class);

Route::post('/nutrition-plan', [NutritionPlanController::class, 'store'])->name('nutrition.plan');
