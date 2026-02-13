<?php

use Illuminate\Support\Facades\Route;
use Sysborg\FocusNfe\app\Http\Controllers\EmpresaController;
use Sysborg\FocusNfe\app\Http\Controllers\NFSeController;
use Sysborg\FocusNfe\app\Http\Controllers\HooksController;
use Sysborg\FocusNfe\app\Http\Controllers\CTeController;
use Sysborg\FocusNfe\app\Http\Controllers\CTERecebidasController;
use Sysborg\FocusNfe\app\Http\Controllers\NFeController;
use Sysborg\FocusNfe\app\Http\Controllers\NFeRecebidasController;
use Sysborg\FocusNfe\app\Http\Controllers\NFSeArquivoController;
use Sysborg\FocusNfe\app\Http\Controllers\NFSeNacionalController;
use Sysborg\FocusNfe\app\Http\Controllers\NFSeRecebidasController;
use Sysborg\FocusNfe\app\Http\Controllers\MDFeController;
use Sysborg\FocusNfe\app\Http\Controllers\NcmController;
use Sysborg\FocusNfe\app\Http\Controllers\CfopController;
use Sysborg\FocusNfe\app\Http\Controllers\MunicipiosController;
use Sysborg\FocusNfe\app\Http\Controllers\ConsultaEmailsController;
use Sysborg\FocusNfe\app\Http\Controllers\CepController;
use Sysborg\FocusNfe\app\Http\Controllers\CnaeController;
use Sysborg\FocusNfe\app\Http\Controllers\CnpjController;
use Sysborg\FocusNfe\app\Http\Controllers\NFCeController;

Route::apiResource('empresas', EmpresaController::class);
Route::apiResource('nfse', NFSeController::class)->only(['store', 'show', 'destroy']);

Route::apiResource('cte', CTeController::class)->only(['store', 'show', 'destroy']);
Route::post('cte/{referencia}/carta_correcao', [CTeController::class, 'cartaCorrecao']);

Route::get('cte_recebidas', [CTERecebidasController::class, 'index']);
Route::get('cte_recebidas/{chave}', [CTERecebidasController::class, 'show']);
Route::post('cte_recebidas/{chave}/desacordo', [CTERecebidasController::class, 'informarDesacordo']);
Route::get('cte_recebidas/{chave}/desacordo', [CTERecebidasController::class, 'consultaDesacordo']);

Route::apiResource('nfe', NFeController::class)->only(['store', 'show', 'destroy']);
Route::post('nfe/{referencia}/carta_correcao', [NFeController::class, 'cartaCorrecao']);
Route::get('nfe/inutilizacoes/lista', [NFeController::class, 'inutilizacoes']);

Route::get('nfe_recebidas/{cnpj}', [NFeRecebidasController::class, 'listByCnpj']);
Route::post('nfe_recebidas/{chave}/manifesto', [NFeRecebidasController::class, 'manifestar']);
Route::get('nfe_recebidas/{chave}/manifesto', [NFeRecebidasController::class, 'consultarManifesto']);

Route::apiResource('nfse_arquivo', NFSeArquivoController::class)->only(['store', 'show']);
Route::apiResource('nfse_nacional', NFSeNacionalController::class)->only(['store', 'show', 'destroy']);
Route::apiResource('nfse_recebidas', NFSeRecebidasController::class)->only(['index', 'show']);

Route::apiResource('mdfe', MDFeController::class)->only(['store', 'show', 'destroy']);
Route::post('mdfe/{referencia}/inclui_condutor', [MDFeController::class, 'incluiCondutor']);
Route::post('mdfe/{referencia}/inclui_dfe', [MDFeController::class, 'incluiDFe']);
Route::post('mdfe/{referencia}/encerra', [MDFeController::class, 'encerra']);

Route::apiResource('municipios', MunicipiosController::class)->only(['index', 'show']);
Route::get('municipios/{codigoMunicipio}/lista_servico/{codigoServico}', [MunicipiosController::class, 'getListaServico']);
Route::get('municipios/{codigoMunicipio}/codigos_tributarios/{codigoTributario}', [MunicipiosController::class, 'getCodigosTributarios']);

Route::apiResource('nfce', NFCeController::class)->only(['store', 'show', 'destroy']);
Route::get('nfce/inutilizacoes/lista', [NFCeController::class, 'inutilizacoes']);
Route::post('nfce/{referencia}/econf', [NFCeController::class, 'registraEconf']);
Route::get('nfce/{referencia}/econf/{protocolo}', [NFCeController::class, 'consultaEconf']);
Route::delete('nfce/{referencia}/econf/{protocolo}', [NFCeController::class, 'cancelaEconf']);

Route::get('consulta_emails/{email}', [ConsultaEmailsController::class, 'getEmail']);
Route::delete('consulta_emails/{email}', [ConsultaEmailsController::class, 'deleteEmail']);
Route::get('consulta-emails/{email}', [ConsultaEmailsController::class, 'getEmail']);
Route::delete('consulta-emails/{email}', [ConsultaEmailsController::class, 'deleteEmail']);

Route::apiResource('ncm', NcmController::class)->only(['index', 'show']);
Route::apiResource('cnae', CnaeController::class)->only(['index', 'show']);
Route::apiResource('cfop', CfopController::class)->only(['index', 'show']);
Route::get('/cep/{cep}', [CepController::class, 'consultar'])->where('cep', '[0-9]{8}');
Route::get('/cnpj/{cnpj}', [CnpjController::class, 'consultar'])->where('cnpj', '[0-9]{14}');

Route::post('/hooks', [HooksController::class, 'webhook']);
