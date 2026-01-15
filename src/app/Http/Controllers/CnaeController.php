<?php 

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\Rules\CnaeRule;
use Facades\Sysborg\FocusNfe\app\Services\CNAE;

class CnaeController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/v1/cnae",
     *  summary="List CNAE records",
     *  description="Retrieves a list of CNAE (Classificação Nacional de Atividades Econômicas) records with optional filters.",
     *  tags={"CNAE"},
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
     *    description="Filter CNAE by specific code.",
     *    @OA\Schema(
     *      type="string",
     *      example="6201-5/01"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="descricao",
     *    in="query",
     *    required=false,
     *    description="Filter CNAE by description.",
     *    @OA\Schema(
     *      type="string",
     *      example="Desenvolvimento de software sob encomenda"
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="CNAE records retrieved successfully.",
     *    @OA\JsonContent(
     *      type="array",
     *      @OA\Items(
     *        @OA\Property(property="codigo", type="string", example="6201-5/01", description="CNAE code."),
     *        @OA\Property(property="descricao", type="string", example="Desenvolvimento de software sob encomenda", description="CNAE description.")
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
   
        return response()->json(Cnae::list(
            $request->query('offset', 1), 
            $request->query('codigo', null), 
            $request->query('descricao', null) 
        ));
    }

    /**
     * @OA\Get(
     *  path="/api/v1/cnae/{codigo}",
     *  summary="Get a specific CNAE",
     *  description="Retrieves details of a CNAE based on the provided code.",
     *  tags={"CNAE"},
     *  @OA\Parameter(
     *    name="codigo",
     *    in="path",
     *    required=true,
     *    description="CNAE code to retrieve details.",
     *    @OA\Schema(
     *      type="string",
     *      example="6201-5/01"
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="CNAE record retrieved successfully.",
     *    @OA\JsonContent(
     *      @OA\Property(property="codigo", type="string", example="6201-5/01", description="CNAE code."),
     *      @OA\Property(property="descricao", type="string", example="Desenvolvimento de software sob encomenda", description="CNAE description.")
     *    )
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Invalid CNAE format.",
     *    @OA\JsonContent(
     *      @OA\Property(property="error", type="string", example="Invalid CNAE format.")
     *    )
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="CNAE not found.",
     *    @OA\JsonContent(
     *      @OA\Property(property="error", type="string", example="CNAE not found.")
     *    )
     *  )
     * )
     */
    public function show(Request $request, string $codigo)
    {

        $request->validate([
            'cnae' => ['required', 'string', new CnaeRule($codigo)]
        ]);

        return response()->json(CNAE::get($codigo));

    }
}
