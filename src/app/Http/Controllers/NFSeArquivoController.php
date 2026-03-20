<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\Http\Requests\NFSeArquivoRequest;
use Facades\Sysborg\FocusNfe\app\Services\NFSeArquivo;

/**
 * Controlador responsável por gerenciar envio de NFSe via arquivo
 */
class NFSeArquivoController extends Controller
{
    /**
     * Envia um arquivo de NFSe para processamento.
     *
     * @param NFSeArquivoRequest $request
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(NFSeArquivoRequest $request, string $referencia)
    {
        return response()->json(NFSeArquivo::envia($referencia, $request->file('arquivo')), 201);
    }

    /**
     * Consulta um lote de NFSe enviado por arquivo.
     *
     * @param string $referencia
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $referencia)
    {
        return response()->json(NFSeArquivo::get($referencia));
    }
}
