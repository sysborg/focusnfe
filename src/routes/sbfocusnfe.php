<?php

use Illuminate\Support\Facades\Route;
use Sysborg\FocusNFe\app\Http\Controllers\EmpresaController;
use Sysborg\FocusNFe\app\Http\Controllers\NFSeController;
use Sysborg\FocusNFe\app\Http\Controllers\HooksController;
use Sysborg\FocusNFe\app\Http\Controllers\CTEController;
use Sysborg\FocusNFe\app\Http\Controllers\CTERecebidasController;
use Sysborg\FocusNFe\app\Http\Controllers\NFeController;
use Sysborg\FocusNFe\app\Http\Controllers\NFeRecebidasController;
use Sysborg\FocusNFe\app\Http\Controllers\NFSeArquivoController;
use Sysborg\FocusNFe\app\Http\Controllers\NFSeNacionalController;
use Sysborg\FocusNFe\app\Http\Controllers\NFSeRecebidasController;
use Sysborg\FocusNFe\app\Http\Controllers\MDFeController;
use Sysborg\FocusNFe\app\Http\Controllers\NcmController;
use Sysborg\FocusNFe\app\Http\Controllers\CFOPController;
use Sysborg\FocusNFe\app\Http\Controllers\MunicipiosController;
use Sysborg\FocusNFe\app\Http\Controllers\ConsultaEmailsController;
use Sysborg\FocusNFe\app\Http\Controllers\CepController;
use Sysborg\FocusNFe\app\Http\Controllers\CnaeController;
use Sysborg\FocusNFe\app\Http\Controllers\CnpjController;

Route::prefix('sbfocus')->middleware(config('focusnfe.middlewares', []))->group(function () {
    Route::apiResource('empresas', EmpresaController::class);
    Route::apiResource('nfse', NFSeController::class);
    Route::apiResource('cte', CTEController::class);
    Route::apiResource('cte_recebidas', CTERecebidasController::class);
    Route::apiResource('nfe', NFeController::class);
    Route::apiResource('nfe_recebidas', NFeRecebidasController::class);
    Route::apiResource('nfse_arquivo', NFSeArquivoController::class);
    Route::apiResource('nfse_nacional', NFSeNacionalController::class);
    Route::apiResource('nfse_recebidas', NFSeRecebidasController::class);
    Route::apiResource('mdfe', MDFeController::class);
    Route::apiResource('ncm', NcmController::class);
    Route::apiResource('cfop', CFOPController::class);
    Route::apiResource('municipios', MunicipiosController::class);
    Route::apiResource('consulta_emails', ConsultaEmailsController::class);
    Route::apiResource('cep', CepController::class);
    Route::apiResource('cnae', CnaeController::class);
    Route::apiResource('cnpj', CnpjController::class);
});

Route::post('/sbfocus/hooks', [HooksController::class, 'webhook']);
