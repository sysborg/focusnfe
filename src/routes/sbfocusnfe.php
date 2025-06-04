<?php

use Illuminate\Support\Facades\Route;
use Sysborg\FocusNFe\app\Http\Controllers\EmpresaController;
use Sysborg\FocusNFe\app\Http\Controllers\NFSeController;
use Sysborg\FocusNFe\app\Http\Controllers\HooksController;
use Sysborg\FocusNFe\app\Http\Controllers\CTeController;
use Sysborg\FocusNFe\app\Http\Controllers\CTERecebidasController;
use Sysborg\FocusNFe\app\Http\Controllers\NFeController;
use Sysborg\FocusNFe\app\Http\Controllers\NFeRecebidasController;
use Sysborg\FocusNFe\app\Http\Controllers\NFSeArquivoController;
use Sysborg\FocusNFe\app\Http\Controllers\NFSeNacionalController;
use Sysborg\FocusNFe\app\Http\Controllers\NFSeRecebidasController;
use Sysborg\FocusNFe\app\Http\Controllers\MDFeController;
use Sysborg\FocusNFe\app\Http\Controllers\NcmController;
use Sysborg\FocusNFe\app\Http\Controllers\CfopController;
use Sysborg\FocusNFe\app\Http\Controllers\MunicipiosController;
use Sysborg\FocusNFe\app\Http\Controllers\ConsultaEmailsController;
use Sysborg\FocusNFe\app\Http\Controllers\CepController;
use Sysborg\FocusNFe\app\Http\Controllers\CnaeController;
use Sysborg\FocusNFe\app\Http\Controllers\CnpjController;

Route::apiResource('empresas', EmpresaController::class);
Route::apiResource('nfse', NFSeController::class);
Route::apiResource('cte', CTeController::class);
Route::apiResource('cte_recebidas', CTERecebidasController::class);
Route::apiResource('nfe', NFeController::class);
Route::apiResource('nfe_recebidas', NFeRecebidasController::class);
Route::apiResource('nfse_arquivo', NFSeArquivoController::class);
Route::apiResource('nfse_nacional', NFSeNacionalController::class);
Route::apiResource('nfse_recebidas', NFSeRecebidasController::class);
Route::apiResource('mdfe', MDFeController::class);
Route::apiResource('municipios', MunicipiosController::class);
Route::apiResource('consulta_emails', ConsultaEmailsController::class);

Route::apiResource('ncm', NcmController::class)->only(['index', 'show']);
Route::apiResource('cnae', CnaeController::class)->only(['index', 'show']);
Route::apiResource('cfop', CfopController::class)->only(['index', 'show']);
Route::get('/cep/{cep}', [CepController::class, 'show'])->where('cep', '[0-9]{8}');
Route::get('/cnpj/{cnpj}', [CnpjController::class, 'consultar'])->where('cnpj', '[0-9]{14}');

Route::post('/sbfocus/hooks', [HooksController::class, 'webhook']);
