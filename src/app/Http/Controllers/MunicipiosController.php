<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Facades\Sysborg\FocusNfe\app\Services\Municipios;

/**
 * Controlador responsável por consultar municípios
 */
class MunicipiosController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        return response()->json(Municipios::list(
            $request->query('offset', 1),
            $request->query('codigo', null),
            $request->query('descricao', null)
        ));
    }


    /**
     * @param string $codigo
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $codigo)
    {
        return response()->json(Municipios::get($codigo));
    }

    /**
     * @param string $codigoMunicipio
     * @param string $codigoServico
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListaServico(string $codigoMunicipio, string $codigoServico)
    {
        return response()->json(Municipios::getListaServico($codigoMunicipio, $codigoServico));
    }


    /**
     * @param string $codigoMunicipio
     * @param string $codigoTributario
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCodigosTributarios(string $codigoMunicipio, string $codigoTributario)
    {
        return response()->json(Municipios::getCodigosTributarios($codigoMunicipio, $codigoTributario));
    }
}
