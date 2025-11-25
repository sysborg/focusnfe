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
  ) {
    $this->validate();
  }

  /**
   * Valida os campos obrigatórios do DTO
   *
   * @throws ValidationException
   * @return void
   */
  protected function validate(): void
  {
    $validator = Validator::make($this->toArray(), self::rules(), self::messages());

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
      'nomeFantasia' => 'nullable|string|max:255',
      'complemento' => 'nullable|string|max:100',
      'inscricaoEstadual' => 'nullable|string|max:20',
      'inscricaoMunicipal' => 'nullable|string|max:20',
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
    );
  }
}
