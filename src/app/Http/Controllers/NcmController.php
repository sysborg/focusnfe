<?php 

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Facades\Sysborg\FocusNfe\app\Services\NCM;

class NcmController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/v1/ncm",
     *  summary="List NCM records",
     *  description="Retrieves a list of NCM (Nomenclatura Comum do Mercosul) records with optional filters.",
     *  tags={"NCM"},
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
     *    description="Filter NCM by specific code.",
     *    @OA\Schema(
     *      type="string",
     *      example="1006.30.21"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="descricao",
     *    in="query",
     *    required=false,
     *    description="Filter NCM by description.",
     *    @OA\Schema(
     *      type="string",
     *      example="Arroz para plantio"
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="NCM records retrieved successfully.",
     *    @OA\JsonContent(
     *      type="array",
     *      @OA\Items(
     *        @OA\Property(property="codigo", type="string", example="1006.30.21", description="NCM code."),
     *        @OA\Property(property="descricao", type="string", example="Arroz para plantio", description="NCM description.")
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
        $response = Ncm::list(
            $request->query('offset', 1),
            $request->query('codigo', null),
            $request->query('descricao', null)
        );

        return response()->json($response->json(), $response->status());
    }

    /**
     * @OA\Get(
     *  path="/api/v1/ncm/{codigo}",
     *  summary="Get a specific NCM",
     *  description="Retrieves details of an NCM based on the provided code.",
     *  tags={"NCM"},
     *  @OA\Parameter(
     *    name="codigo",
     *    in="path",
     *    required=true,
     *    description="NCM code to retrieve details.",
     *    @OA\Schema(
     *      type="string",
     *      example="1006.30.21"
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="NCM record retrieved successfully.",
     *    @OA\JsonContent(
     *      @OA\Property(property="codigo", type="string", example="1006.30.21", description="NCM code."),
     *      @OA\Property(property="descricao", type="string", example="Arroz para plantio", description="NCM description.")
     *    )
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Invalid NCM format.",
     *    @OA\JsonContent(
     *      @OA\Property(property="error", type="string", example="Invalid NCM format.")
     *    )
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="NCM not found.",
     *    @OA\JsonContent(
     *      @OA\Property(property="error", type="string", example="NCM not found.")
     *    )
     *  )
     * )
     */
    public function show(string $codigo)
    {
        return response()->json(Ncm::get($codigo));
    }
}
