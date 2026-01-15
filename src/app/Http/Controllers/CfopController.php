<?php 

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\Services\CFOP; 

class CfopController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/v1/cfop",
     *  summary="List CFOP records",
     *  description="Retrieves a list of CFOP (Código Fiscal de Operações e Prestações) records with optional filters.",
     *  tags={"CFOP"},
     *  @OA\Parameter(
     *    name="offset",
     *    in="query",
     *    required=false,
     *    description="Pagination offset, default is 1.",
     *    @OA\Schema(
     *      type="integer",
     *      example=1
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="codigo",
     *    in="query",
     *    required=false,
     *    description="Filter CFOP by specific code.",
     *    @OA\Schema(
     *      type="string",
     *      example="5102"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="descricao",
     *    in="query",
     *    required=false,
     *    description="Filter CFOP by description.",
     *    @OA\Schema(
     *      type="string",
     *      example="Venda de mercadoria"
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="CFOP records retrieved successfully.",
     *    @OA\JsonContent(
     *      type="array",
     *      @OA\Items(
     *        @OA\Property(property="codigo", type="string", example="5102", description="CFOP code."),
     *        @OA\Property(property="descricao", type="string", example="Venda de mercadoria", description="CFOP description.")
     *      )
     *    )
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Invalid request parameters.",
     *    @OA\JsonContent(
     *      @OA\Property(property="error", type="string", example="Invalid offset value.")
     *    )
     *  )
     * )
     */
    public function index(Request $request)
    {
        return response()->json(Cfop::list(
            $request->query('offset', 1),
            $request->query('codigo', null),
            $request->query('descricao', null)
        ));
    }

    /**
     * @OA\Get(
     *  path="/api/v1/cfop/{codigo}",
     *  summary="Get a specific CFOP",
     *  description="Retrieves details of a CFOP based on the provided code.",
     *  tags={"CFOP"},
     *  @OA\Parameter(
     *    name="codigo",
     *    in="path",
     *    required=true,
     *    description="CFOP code to retrieve details.",
     *    @OA\Schema(
     *      type="string",
     *      example="5102"
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="CFOP record retrieved successfully.",
     *    @OA\JsonContent(
     *      @OA\Property(property="codigo", type="string", example="5102", description="CFOP code."),
     *      @OA\Property(property="descricao", type="string", example="Venda de mercadoria", description="CFOP description.")
     *    )
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="CFOP not found.",
     *    @OA\JsonContent(
     *      @OA\Property(property="error", type="string", example="CFOP not found.")
     *    )
     *  )
     * )
     */
    public function show(string $codigo)
    {
        return response()->json(Cfop::get($codigo));
    }
}
