<?php 

namespace Sysborg\FocusNfe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNfe\app\Rules\CnpjRule;
use Facades\Sysborg\FocusNfe\app\Services\Cnpjs;

class CnpjController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/v1/cnpj/{cnpj}",
     *  summary="Retrieve company details by CNPJ",
     *  description="Fetches company details based on the provided CNPJ (Cadastro Nacional da Pessoa Jurídica).",
     *  tags={"CNPJ"},
     *  @OA\Parameter(
     *    name="cnpj",
     *    in="path",
     *    required=true,
     *    description="The CNPJ number of the company to be retrieved.",
     *    @OA\Schema(
     *      type="string",
     *      example="12345678000195"
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Company data retrieved successfully.",
     *    @OA\JsonContent(
     *      @OA\Property(property="cnpj", type="string", example="12.345.678/0001-95", description="Formatted CNPJ."),
     *      @OA\Property(property="razao_social", type="string", example="Empresa XYZ Ltda", description="Company's legal name."),
     *      @OA\Property(property="nome_fantasia", type="string", example="Empresa XYZ", description="Trade name of the company."),
     *      @OA\Property(property="situacao", type="string", example="Ativa", description="Company's registration status."),
     *      @OA\Property(property="data_abertura", type="string", format="date", example="2000-05-15", description="Company's opening date."),
     *      @OA\Property(property="endereco", type="object",
     *        @OA\Property(property="logradouro", type="string", example="Rua das Flores", description="Street address."),
     *        @OA\Property(property="numero", type="string", example="100", description="Address number."),
     *        @OA\Property(property="bairro", type="string", example="Centro", description="Neighborhood."),
     *        @OA\Property(property="cidade", type="string", example="São Paulo", description="City."),
     *        @OA\Property(property="estado", type="string", example="SP", description="State abbreviation."),
     *        @OA\Property(property="cep", type="string", example="01001-000", description="Postal code.")
     *      )
     *    )
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Invalid CNPJ format.",
     *    @OA\JsonContent(
     *      @OA\Property(property="error", type="string", example="Invalid CNPJ format.")
     *    )
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="CNPJ not found.",
     *    @OA\JsonContent(
     *      @OA\Property(property="error", type="string", example="CNPJ not found.")
     *    )
     *  )
     * )
     */
    public function consultar(Request $request, string $cnpj)
    {
        validator(['cnpj' => $cnpj], [
            'cnpj' => ['required', 'string', new CnpjRule()]
        ])->validate();

        return response()->json(Cnpjs::get($cnpj));
    }
}
