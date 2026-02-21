<?php
namespace Sysborg\FocusNfe\app\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EmpresaDTO extends DTO
{
  public function __construct(
    public string $razaoSocial,
    public string $nomeFantasia,
    public string $bairro,
    public int $cep,
    public string $cnpj,
    public string $complemento,
    public string $email,
    public string $inscricaoEstadual,
    public string $inscricaoMunicipal,
    public string $logradouro,
    public int $numero,
    public int $regimeTributario,
    public string $telefone,
    public string $municipio,
    public string $uf,
    public bool $habilitaNfe,
    public bool $habilitaNfce,
    public bool $habilitaNfse,
    public string $arquivoCertificado,
    public string $senhaCertificado,
    public string $cscNfceProducao,
    public string $idTokenNfceProducao,
    public bool $enviaEmailDestinatario = true,
    public bool $discriminaImposto = true,
    public bool $certificadoEspecifico = false,
    public ?int $proximoNumeroNfseProducao = null,
    public ?int $proximoNumeroNfseHomologacao = null,
    public ?int $serieNfseProducao = null,
    public ?int $serieNfseHomologacao = null,
    public ?int $proximoNumeroNfeProducao = null,
    public ?int $proximoNumeroNfeHomologacao = null,
    public ?int $serieNfeProducao = null,
    public ?int $serieNfeHomologacao = null,
    public ?int $proximoNumeroNfceProducao = null,
    public ?int $proximoNumeroNfceHomologacao = null,
    public ?int $serieNfceProducao = null,
    public ?int $serieNfceHomologacao = null,
  ) {
    $this->validate();
  }

  /**
   * Mapeamento de campos que não seguem a conversão automática
   *
   * @return array
   */
  protected static function fieldMapping(): array
  {
    return [
      'razaoSocial' => 'nome',
      'arquivoCertificado' => 'arquivo_certificado_base64',
    ];
  }

  /**
   * Valida os campos obrigatórios do DTO
   *
   * @throws ValidationException
   * @return void
   */
  protected function validate(): void
  {
    // Valida os dados em camelCase (antes da conversão para snake_case)
    $validator = Validator::make(get_object_vars($this), self::rules(), self::messages());

    if ($validator->fails()) {
      throw new ValidationException($validator);
    }
  }

  /**
   * Regras de validação
   *
   * @return array
   */
  public static function rules(): array
  {
    return [
      'razaoSocial' => 'required|string|max:255',
      'cnpj' => 'required|cnpj',
      'email' => 'required|email|max:255',
      'bairro' => 'required|string|max:100',
      'cep' => 'required|integer',
      'logradouro' => 'required|string|max:255',
      'numero' => 'required|integer|min:0',
      'municipio' => 'required|string|max:100',
      'uf' => 'required|string|size:2',
      'regimeTributario' => 'required|integer|between:1,3',
      'telefone' => 'required|string|max:20',
      'habilitaNfe' => 'required|boolean',
      'habilitaNfce' => 'required|boolean',
      'habilitaNfse' => 'required|boolean',
      'enviaEmailDestinatario' => 'boolean',
      'discriminaImposto' => 'boolean',
      'certificadoEspecifico' => 'boolean',
      'cscNfceProducao' => 'required_if:habilitaNfce,true|string|max:100',
      'idTokenNfceProducao' => 'required_if:habilitaNfce,true|string|max:10',
      'arquivoCertificado' => 'required_if:certificadoEspecifico,true|string',
      'senhaCertificado' => 'required_if:certificadoEspecifico,true|string|max:100',
      'nomeFantasia' => 'required|string|max:255',
      'complemento' => 'nullable|string|max:100',
      'inscricaoEstadual' => 'required_if:habilitaNfe,true|required_if:habilitaNfce,true|nullable|string|max:20',
      'inscricaoMunicipal' => 'required_if:habilitaNfse,true|string|max:20',
      'proximoNumeroNfseProducao' => 'nullable|integer|min:1',
      'proximoNumeroNfseHomologacao' => 'nullable|integer|min:1',
      'serieNfseProducao' => 'nullable|integer|min:1',
      'serieNfseHomologacao' => 'nullable|integer|min:1',
      'proximoNumeroNfeProducao' => 'nullable|integer|min:1',
      'proximoNumeroNfeHomologacao' => 'nullable|integer|min:1',
      'serieNfeProducao' => 'nullable|integer|min:1',
      'serieNfeHomologacao' => 'nullable|integer|min:1',
      'proximoNumeroNfceProducao' => 'nullable|integer|min:1',
      'proximoNumeroNfceHomologacao' => 'nullable|integer|min:1',
      'serieNfceProducao' => 'nullable|integer|min:1',
      'serieNfceHomologacao' => 'nullable|integer|min:1',
    ];
  }

  /**
   * Mensagens de validação customizadas
   *
   * @return array
   */
  public static function messages(): array
  {
    return [
      'razaoSocial.required' => 'A razão social é obrigatória',
      'razaoSocial.string' => 'A razão social deve ser um texto',
      'razaoSocial.max' => 'A razão social não pode ter mais de 255 caracteres',
      'cnpj.required' => 'O CNPJ é obrigatório',
      'cnpj.cnpj' => 'O CNPJ informado é inválido',
      'email.required' => 'O email é obrigatório',
      'email.email' => 'O email informado é inválido',
      'email.max' => 'O email não pode ter mais de 255 caracteres',
      'bairro.required' => 'O bairro é obrigatório',
      'bairro.max' => 'O bairro não pode ter mais de 100 caracteres',
      'cep.required' => 'O CEP é obrigatório',
      'cep.integer' => 'O CEP deve ser numérico',
      'logradouro.required' => 'O logradouro é obrigatório',
      'logradouro.max' => 'O logradouro não pode ter mais de 255 caracteres',
      'numero.required' => 'O número é obrigatório',
      'numero.integer' => 'O número deve ser numérico',
      'numero.min' => 'O número deve ser maior ou igual a zero',
      'complemento.max' => 'O complemento não pode ter mais de 100 caracteres',
      'municipio.required' => 'O município é obrigatório',
      'municipio.max' => 'O município não pode ter mais de 100 caracteres',
      'uf.required' => 'A UF é obrigatória',
      'uf.size' => 'A UF deve ter exatamente 2 caracteres',
      'regimeTributario.required' => 'O regime tributário é obrigatório',
      'regimeTributario.integer' => 'O regime tributário deve ser numérico',
      'regimeTributario.between' => 'O regime tributário deve ser 1 (Simples Nacional), 2 (Simples Nacional - excesso) ou 3 (Regime Normal)',
      'telefone.required' => 'O telefone é obrigatório',
      'telefone.max' => 'O telefone não pode ter mais de 20 caracteres',
      'inscricaoEstadual.max' => 'A inscrição estadual não pode ter mais de 20 caracteres',
      'inscricaoMunicipal.max' => 'A inscrição municipal não pode ter mais de 20 caracteres',
      'cscNfceProducao.required_if' => 'O CSC de produção é obrigatório quando NFCe está habilitada',
      'cscNfceProducao.max' => 'O CSC não pode ter mais de 100 caracteres',
      'idTokenNfceProducao.required_if' => 'O ID Token de produção é obrigatório quando NFCe está habilitada',
      'idTokenNfceProducao.max' => 'O ID Token não pode ter mais de 10 caracteres',
      'arquivoCertificado.required_if' => 'O arquivo do certificado é obrigatório quando certificado específico está habilitado',
      'senhaCertificado.required_if' => 'A senha do certificado é obrigatória quando certificado específico está habilitado',
      'senhaCertificado.max' => 'A senha do certificado não pode ter mais de 100 caracteres',
      'proximoNumeroNfseProducao.integer' => 'O próximo número de NFSe (produção) deve ser numérico',
      'proximoNumeroNfseProducao.min' => 'O próximo número de NFSe (produção) deve ser maior ou igual a 1',
      'proximoNumeroNfseHomologacao.integer' => 'O próximo número de NFSe (homologação) deve ser numérico',
      'proximoNumeroNfseHomologacao.min' => 'O próximo número de NFSe (homologação) deve ser maior ou igual a 1',
      'serieNfseProducao.integer' => 'A série de NFSe (produção) deve ser numérica',
      'serieNfseProducao.min' => 'A série de NFSe (produção) deve ser maior ou igual a 1',
      'serieNfseHomologacao.integer' => 'A série de NFSe (homologação) deve ser numérica',
      'serieNfseHomologacao.min' => 'A série de NFSe (homologação) deve ser maior ou igual a 1',
      'proximoNumeroNfeProducao.integer' => 'O próximo número de NFe (produção) deve ser numérico',
      'proximoNumeroNfeProducao.min' => 'O próximo número de NFe (produção) deve ser maior ou igual a 1',
      'proximoNumeroNfeHomologacao.integer' => 'O próximo número de NFe (homologação) deve ser numérico',
      'proximoNumeroNfeHomologacao.min' => 'O próximo número de NFe (homologação) deve ser maior ou igual a 1',
      'serieNfeProducao.integer' => 'A série de NFe (produção) deve ser numérica',
      'serieNfeProducao.min' => 'A série de NFe (produção) deve ser maior ou igual a 1',
      'serieNfeHomologacao.integer' => 'A série de NFe (homologação) deve ser numérica',
      'serieNfeHomologacao.min' => 'A série de NFe (homologação) deve ser maior ou igual a 1',
      'proximoNumeroNfceProducao.integer' => 'O próximo número de NFCe (produção) deve ser numérico',
      'proximoNumeroNfceProducao.min' => 'O próximo número de NFCe (produção) deve ser maior ou igual a 1',
      'proximoNumeroNfceHomologacao.integer' => 'O próximo número de NFCe (homologação) deve ser numérico',
      'proximoNumeroNfceHomologacao.min' => 'O próximo número de NFCe (homologação) deve ser maior ou igual a 1',
      'serieNfceProducao.integer' => 'A série de NFCe (produção) deve ser numérica',
      'serieNfceProducao.min' => 'A série de NFCe (produção) deve ser maior ou igual a 1',
      'serieNfceHomologacao.integer' => 'A série de NFCe (homologação) deve ser numérica',
      'serieNfceHomologacao.min' => 'A série de NFCe (homologação) deve ser maior ou igual a 1',
    ];
  }

  /**
   * Cria um objeto EmpresaDTO a partir de um array
   *
   * @param array $data Array com os dados da empresa em camelCase
   * @return EmpresaDTO
   * @throws ValidationException
   */
  public static function fromArray(array $data): self
  {
    return new self(
        $data['razaoSocial'],
        $data['nomeFantasia'] ?? '',
        $data['bairro'],
        $data['cep'],
        $data['cnpj'],
        $data['complemento'] ?? '',
        $data['email'],
        $data['inscricaoEstadual'] ?? '',
        $data['inscricaoMunicipal'] ?? '',
        $data['logradouro'],
        $data['numero'],
        $data['regimeTributario'],
        $data['telefone'],
        $data['municipio'],
        $data['uf'],
        $data['habilitaNfe'],
        $data['habilitaNfce'],
        $data['habilitaNfse'] ?? false,
        $data['arquivoCertificado'] ?? '',
        $data['senhaCertificado'] ?? '',
        $data['cscNfceProducao'] ?? '',
        $data['idTokenNfceProducao'] ?? '',
        $data['enviaEmailDestinatario'] ?? true,
        $data['discriminaImposto'] ?? true,
        $data['certificadoEspecifico'] ?? false,
        $data['proximoNumeroNfseProducao'] ?? null,
        $data['proximoNumeroNfseHomologacao'] ?? null,
        $data['serieNfseProducao'] ?? null,
        $data['serieNfseHomologacao'] ?? null,
        $data['proximoNumeroNfeProducao'] ?? null,
        $data['proximoNumeroNfeHomologacao'] ?? null,
        $data['serieNfeProducao'] ?? null,
        $data['serieNfeHomologacao'] ?? null,
        $data['proximoNumeroNfceProducao'] ?? null,
        $data['proximoNumeroNfceHomologacao'] ?? null,
        $data['serieNfceProducao'] ?? null,
        $data['serieNfceHomologacao'] ?? null,
    );
  }
}
