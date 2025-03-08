<?php

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\Http\Requests\NFSeArquivoRequest;
use Facades\Sysborg\FocusNFe\app\Services\NFSeArquivo;

class NFSeArquivoController extends Controller
{
    /**
     * Envia um arquivo de NFSe para processamento.
     */
    public function store(NFSeArquivoRequest $request, string $referencia)
    {
        return response()->json(NFSeArquivo::envia($referencia, $request->file('arquivo')), 201);
    }

    /**
     * Consulta um lote de NFSe enviado por arquivo.
     */
    public function show(string $referencia)
    {
        return response()->json(NFSeArquivo::consulta($referencia));
    }
}
