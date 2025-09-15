<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProprietariosController;
use App\Http\Controllers\VeiculosController;
use App\Http\Controllers\RevisoesController;
use App\Http\Controllers\RelatoriosController;


Route::apiResource('proprietarios', ProprietariosController::class);
Route::apiResource('veiculos', VeiculosController::class);
Route::apiResource('revisoes', RevisoesController::class);

Route::get('relatorios/veiculos', [RelatoriosController::class, 'veiculos']);
Route::get('relatorios/veiculos-por-pessoa', [RelatoriosController::class, 'veiculosPorPessoa']);
Route::get('relatorios/veiculos-por-sexo', [RelatoriosController::class, 'veiculosPorSexo']);
Route::get('relatorios/marcas-por-quantidade', [RelatoriosController::class, 'marcasPorQuantidade']);
Route::get('relatorios/marcas-por-sexo', [RelatoriosController::class, 'marcasPorSexo']);
Route::get('relatorios/pessoas', [RelatoriosController::class, 'pessoas']);
Route::get('relatorios/pessoas-por-sexo-idade-media', [RelatoriosController::class, 'pessoasPorSexoComIdadeMedia']);
Route::get('relatorios/revisoes-por-periodo', [RelatoriosController::class, 'revisoesPorPeriodo']);
Route::get('relatorios/marcas-com-mais-revisoes', [RelatoriosController::class, 'marcasComMaisRevisoes']);
Route::get('relatorios/pessoas-com-mais-revisoes', [RelatoriosController::class, 'pessoasComMaisRevisoes']);
Route::get('relatorios/media-tempo-entre-revisoes', [RelatoriosController::class, 'mediaTempoEntreRevisoesPorPessoa']);
Route::get('relatorios/proximas-revisoes', [RelatoriosController::class, 'proximasRevisoes']);