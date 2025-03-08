<?php 

namespace Sysborg\FocusNFe\app\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\Rules\CepRule;
use Facades\Sysborg\FocusNFe\app\Services\CEP;

class CepController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/v1/cep/{cep}",
     *  summary="Retrieve address by ZIP code",
     *  description="Fetches address details based on the provided ZIP code (CEP).",
     *  tags={"CEP"},
     *  @OA\Parameter(
     *    name="cep",
     *    in="path",
     *    required=true,
     *    description="The ZIP code (CEP) to look up.",
     *    @OA\Schema(
     *      type="string",
     *      example="01001000"
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Address data retrieved successfully.",
     *    @OA\JsonContent(
     *      @OA\Property(
     *        property="cep",
     *        type="string",
     *        example="01001-000",
     *        description="Formatted ZIP code."
     *      ),
     *      @OA\Property(
     *        property="logradouro",
     *        type="string",
     *        example="Praça da Sé",
     *        description="Street name."
     *      ),
     *      @OA\Property(
     *        property="bairro",
     *        type="string",
     *        example="Sé",
     *        description="Neighborhood."
     *      ),
     *      @OA\Property(
     *        property="cidade",
     *        type="string",
     *        example="São Paulo",
     *        description="City name."
     *      ),
     *      @OA\Property(
     *        property="estado",
     *        type="string",
     *        example="SP",
     *        description="State abbreviation."
     *      )
     *    )
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Invalid ZIP code format.",
     *    @OA\JsonContent(
     *      @OA\Property(
     *        property="error",
     *        type="string",
     *        example="Invalid ZIP code format."
     *      )
     *    )
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="ZIP code not found.",
     *    @OA\JsonContent(
     *      @OA\Property(
     *        property="error",
     *        type="string",
     *        example="ZIP code not found."
     *      )
     *    )
     *  )
     * )
     */
    public function consultar(Request $request, string $cep)
    {
        $request->validate([
            'cep' => ['required', 'string', new CepRule($cep)]
        ]);

        return response()->json(Cep::get($cep));
    }
}
