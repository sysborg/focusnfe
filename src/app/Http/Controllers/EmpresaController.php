<?php 

namespace Sysborg\FocusNFe\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sysborg\FocusNFe\app\DTO\EmpresaDTO;
use Sysborg\FocusNFe\app\Http\Requests\EmpresaRequest;
use Sysborg\FocusNfe\app\Services\Empresas;

class EmpresaController extends Controller
{
    /**
     * @OA\Get(
     *  path="/api/v1/empresas",
     *  summary="List companies",
     *  description="Retrieves a list of registered companies with optional filters.",
     *  tags={"Empresas"},
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
     *    name="cnpj",
     *    in="query",
     *    required=false,
     *    description="Filter companies by CNPJ.",
     *    @OA\Schema(
     *      type="string",
     *      example="12345678000195"
     *    )
     *  ),
     *  @OA\Parameter(
     *    name="cpf",
     *    in="query",
     *    required=false,
     *    description="Filter companies by CPF (if applicable).",
     *    @OA\Schema(
     *      type="string",
     *      example="12345678901"
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Company list retrieved successfully.",
     *    @OA\JsonContent(
     *      type="array",
     *      @OA\Items(
     *        @OA\Property(property="id", type="string", example="1", description="Company ID."),
     *        @OA\Property(property="cnpj", type="string", example="12.345.678/0001-95", description="CNPJ of the company."),
     *        @OA\Property(property="cpf", type="string", example="123.456.789-01", description="CPF of the responsible person (if applicable)."),
     *        @OA\Property(property="razao_social", type="string", example="Empresa XYZ Ltda", description="Company's legal name.")
     *      )
     *    )
     *  )
     * )
     */
    public function index(Request $request)
    {
        return response()->json(Empresas::list(
            $request->query('offset', 1),
            $request->query('cnpj', null),
            $request->query('cpf', null)
        ));
    }

    /**
     * @OA\Post(
     *  path="/api/v1/empresas",
     *  summary="Create a new company",
     *  description="Registers a new company in the system.",
     *  tags={"Empresas"},
     *  @OA\RequestBody(
     *    required=true,
     *    description="Company data",
     *    @OA\JsonContent(
     *      required={"cnpj", "razao_social"},
     *      @OA\Property(property="cnpj", type="string", example="12.345.678/0001-95", description="CNPJ of the company."),
     *      @OA\Property(property="cpf", type="string", example="123.456.789-01", description="CPF of the responsible person (if applicable)."),
     *      @OA\Property(property="razao_social", type="string", example="Empresa XYZ Ltda", description="Company's legal name."),
     *      @OA\Property(property="nome_fantasia", type="string", example="Empresa XYZ", description="Trade name of the company.")
     *    )
     *  ),
     *  @OA\Response(
     *    response=201,
     *    description="Company created successfully.",
     *    @OA\JsonContent(
     *      @OA\Property(property="id", type="string", example="1", description="Company ID."),
     *      @OA\Property(property="cnpj", type="string", example="12.345.678/0001-95", description="CNPJ of the company."),
     *      @OA\Property(property="razao_social", type="string", example="Empresa XYZ Ltda", description="Company's legal name.")
     *    )
     *  )
     * )
     */
    public function store(EmpresaRequest $request)
    {
        $dto = EmpresaDTO::fromArray($request->validated());
        return response()->json(Empresas::create($dto), 201);
    }
    
    /**
     * @OA\Get(
     *  path="/api/v1/empresas/{id}",
     *  summary="Get company details",
     *  description="Retrieves details of a specific company by ID.",
     *  tags={"Empresas"},
     *  @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    description="Company ID.",
     *    @OA\Schema(
     *      type="string",
     *      example="1"
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Company details retrieved successfully.",
     *    @OA\JsonContent(
     *      @OA\Property(property="id", type="string", example="1", description="Company ID."),
     *      @OA\Property(property="cnpj", type="string", example="12.345.678/0001-95", description="CNPJ of the company."),
     *      @OA\Property(property="razao_social", type="string", example="Empresa XYZ Ltda", description="Company's legal name.")
     *    )
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="Company not found.",
     *    @OA\JsonContent(
     *      @OA\Property(property="error", type="string", example="Company not found.")
     *    )
     *  )
     * )
     */
    public function show(string $id)
    {
        return response()->json(Empresas::get($id));
    }

    /**
     * @OA\Put(
     *  path="/api/v1/empresas/{id}",
     *  summary="Update company information",
     *  description="Updates the information of an existing company by ID.",
     *  tags={"Empresas"},
     *  @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    description="Company ID.",
     *    @OA\Schema(
     *      type="string",
     *      example="1"
     *    )
     *  ),
     *  @OA\RequestBody(
     *    required=true,
     *    description="Updated company data",
     *    @OA\JsonContent(
     *      @OA\Property(property="cnpj", type="string", example="12.345.678/0001-95", description="CNPJ of the company."),
     *      @OA\Property(property="razao_social", type="string", example="Empresa XYZ Ltda", description="Company's legal name."),
     *      @OA\Property(property="nome_fantasia", type="string", example="Empresa XYZ", description="Trade name of the company.")
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Company updated successfully.",
     *    @OA\JsonContent(
     *      @OA\Property(property="id", type="string", example="1", description="Company ID."),
     *      @OA\Property(property="cnpj", type="string", example="12.345.678/0001-95", description="CNPJ of the company."),
     *      @OA\Property(property="razao_social", type="string", example="Empresa XYZ Ltda", description="Company's legal name.")
     *    )
     *  ),
     *  @OA\Response(
     *    response=400,
     *    description="Invalid request data.",
     *    @OA\JsonContent(
     *      @OA\Property(property="error", type="string", example="Invalid CNPJ format.")
     *    )
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="Company not found.",
     *    @OA\JsonContent(
     *      @OA\Property(property="error", type="string", example="Company not found.")
     *    )
     *  )
     * )
     */
    public function update(EmpresaRequest $request, string $id)
    {
        $dto = EmpresaDTO::fromArray($request->validated());
        return response()->json(Empresas::update($id, $dto));
    }
    
    /**
     * @OA\Delete(
     *  path="/api/v1/empresas/{id}",
     *  summary="Delete a company",
     *  description="Deletes a specific company by ID.",
     *  tags={"Empresas"},
     *  @OA\Parameter(
     *    name="id",
     *    in="path",
     *    required=true,
     *    description="Company ID.",
     *    @OA\Schema(
     *      type="string",
     *      example="1"
     *    )
     *  ),
     *  @OA\Response(
     *    response=200,
     *    description="Company deleted successfully.",
     *    @OA\JsonContent(
     *      @OA\Property(property="message", type="string", example="Company deleted successfully.")
     *    )
     *  ),
     *  @OA\Response(
     *    response=404,
     *    description="Company not found.",
     *    @OA\JsonContent(
     *      @OA\Property(property="error", type="string", example="Company not found.")
     *    )
     *  )
     * )
     */
    public function destroy(string $id)
    {
        return response()->json(Empresas::delete($id));
    }
}
