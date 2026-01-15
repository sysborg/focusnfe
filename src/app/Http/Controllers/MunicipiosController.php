<?php

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Facades\Sysborg\FocusNfe\app\Services\Municipios;


class MunicipiosController extends Controller
{
  
    public function index(Request $request)
    {
        return response()->json(Municipios::list(
            $request->query('offset', 1),
            $request->query('codigo', null),
            $request->query('descricao', null)
        ));
    }

   
    public function show(string $codigo)
    {
        return response()->json(Municipios::get($codigo));
    }


    public function getListaServico(string $codigoMunicipio, string $codigoServico)
    {
        return response()->json(Municipios::getListaServico($codigoMunicipio, $codigoServico));
    }


    public function getCodigosTributarios(string $codigoMunicipio, string $codigoTributario)
    {
        return response()->json(Municipios::getCodigosTributarios($codigoMunicipio, $codigoTributario));
    }
}
