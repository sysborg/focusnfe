<?php

use Illuminate\Support\Facades\Route;
use Sysborg\FocusNFe\App\Http\Controllers\EmpresaController;
use Sysborg\FocusNFe\App\Http\Controllers\NFSeController;

Route::prefix('sbfocus')->middleware(config('focusnfe.middlewares', []))->group(function () {
    Route::apiResource('empresas', EmpresaController::class);
    Route::apiResource('nfse', NFSeController::class);
});
