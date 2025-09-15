<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProprietariosController;
use App\Http\Controllers\VeiculosController;
use App\Http\Controllers\RevisoesController;
use App\Http\Controllers\RelatoriosController;

Route::apiResource('proprietarios', ProprietariosController::class);
Route::apiResource('veiculos', VeiculosController::class);
Route::apiResource('revisoes', RevisoesController::class);

// Rotas para relatórios
Route::prefix('relatorios')->group(function () {
    Route::get('veiculos', [RelatoriosController::class, 'veiculos']);
    Route::get('veiculos-por-pessoa', [RelatoriosController::class, 'veiculosPorPessoa']);
    Route::get('veiculos-por-sexo', [RelatoriosController::class, 'veiculosPorSexo']);
    Route::get('marcas-por-quantidade', [RelatoriosController::class, 'marcasPorQuantidade']);
    Route::get('marcas-por-sexo', [RelatoriosController::class, 'marcasPorSexo']);
    Route::get('pessoas', [RelatoriosController::class, 'pessoas']);
    Route::get('revisoes-por-periodo', [RelatoriosController::class, 'revisoesPorPeriodo']);
    Route::get('marcas-com-mais-revisoes', [RelatoriosController::class, 'marcasComMaisRevisoes']);
    Route::get('pessoas-com-mais-revisoes', [RelatoriosController::class, 'pessoasComMaisRevisoes']);
    Route::get('media-tempo-entre-revisoes', [RelatoriosController::class, 'mediaTempoEntreRevisoes']);
    Route::get('proximas-revisoes', [RelatoriosController::class, 'proximasRevisoes']);
});
