<?php
namespace Sysborg\FocusNfe\app\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EmpresaDTO extends DTO {
  public function __construct(
    public string $razao_social,
    public string $nome_fantasia,
    public string $bairro,
    public int $cep,
    public string $cnpj,
    public string $complemento,
    public string $email,
    public string $inscricao_estadual,
    public string $inscricao_municipal,
    public string $logradouro,
    public int $numero,
    public int $regime_tributario,
    public string $telefone,
    public string $municipio,
    public string $uf,
    public bool $habilita_nfe,
    public bool $habilita_nfce,
    public string $arquivo_certificado,
    public string $senha_certificado,
    public string $csc_nfce_producao,
    public string $id_token_nfce_producao,
    public bool $envia_email_destinatario = true,
    public bool $discrimina_imposto = true,
  ) {}

  /**
   * Cria um objeto EmpresaDTO a partir de um array
   * 
   * @param array $data
   * @return EmpresaDTO
   */
  public static function fromArray(array $data): self
{
    // Validação dos dados
    $validatedData = Validator::make($data, [
        'razao_social' => 'required|string',
        'nome_fantasia' => 'nullable|string',
        'bairro' => 'required|string',
        'cep' => 'required|integer',
        'cnpj' => 'required|string',
        'complemento' => 'nullable|string',
        'email' => 'required|email',
        'inscricao_estadual' => 'nullable|string',
        'inscricao_municipal' => 'nullable|string',
        'logradouro' => 'required|string',
        'numero' => 'required|integer',
        'regime_tributario' => 'required|integer',
        'telefone' => 'required|string',
        'municipio' => 'required|string',
        'uf' => 'required|string|size:2',
        'habilita_nfe' => 'required|boolean',
        'habilita_nfce' => 'required|boolean',
        'arquivo_certificado' => 'nullable|string',
        'senha_certificado' => 'nullable|string',
        'csc_nfce_producao' => 'nullable|string',
        'id_token_nfce_producao' => 'nullable|string',
        'envia_email_destinatario' => 'required|boolean',
        'discrimina_imposto' => 'required|boolean',
    ])->validate(); // Aplica a validação

    return new self(
        $validatedData['razao_social'],
        $validatedData['nome_fantasia'] ?? '',
        $validatedData['bairro'],
        $validatedData['cep'],
        $validatedData['cnpj'],
        $validatedData['complemento'] ?? '',
        $validatedData['email'],
        $validatedData['inscricao_estadual'] ?? '',
        $validatedData['inscricao_municipal'] ?? '',
        $validatedData['logradouro'],
        $validatedData['numero'],
        $validatedData['regime_tributario'],
        $validatedData['telefone'],
        $validatedData['municipio'],
        $validatedData['uf'],
        $validatedData['habilita_nfe'],
        $validatedData['habilita_nfce'],
        $validatedData['arquivo_certificado'] ?? '',
        $validatedData['senha_certificado'] ?? '',
        $validatedData['csc_nfce_producao'] ?? '',
        $validatedData['id_token_nfce_producao'] ?? '',
        $validatedData['envia_email_destinatario'],
        $validatedData['discrimina_imposto'],
    );
}

}
