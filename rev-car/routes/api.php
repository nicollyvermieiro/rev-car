<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProprietarioController;
use App\Http\Controllers\VeiculoController;
use App\Http\Controllers\RevisaoController;
use App\Http\Controllers\RelatorioController;


Route::apiResource('proprietarios', ProprietarioController::class);
Route::apiResource('veiculos', VeiculoController::class);
Route::apiResource('revisoes', RevisaoController::class);

Route::get('relatorios/veiculos', [RelatorioController::class, 'veiculos']);
Route::get('relatorios/veiculos-por-pessoa', [RelatorioController::class, 'veiculosPorPessoa']);
Route::get('relatorios/veiculos-por-sexo', [RelatorioController::class, 'veiculosPorSexo']);
Route::get('relatorios/marcas-por-quantidade', [RelatorioController::class, 'marcasPorQuantidade']);
Route::get('relatorios/marcas-por-sexo', [RelatorioController::class, 'marcasPorSexo']);
Route::get('relatorios/pessoas', [RelatorioController::class, 'pessoas']);
Route::get('relatorios/pessoas-por-sexo-idade-media', [RelatorioController::class, 'pessoasPorSexoComIdadeMedia']);
Route::get('relatorios/revisoes-por-periodo', [RelatorioController::class, 'revisoesPorPeriodo']);
Route::get('relatorios/marcas-com-mais-revisoes', [RelatorioController::class, 'marcasComMaisRevisoes']);
Route::get('relatorios/pessoas-com-mais-revisoes', [RelatorioController::class, 'pessoasComMaisRevisoes']);
Route::get('relatorios/media-tempo-entre-revisoes', [RelatorioController::class, 'mediaTempoEntreRevisoesPorPessoa']);
Route::get('relatorios/proximas-revisoes', [RelatorioController::class, 'proximasRevisoes']);