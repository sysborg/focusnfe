<?php
namespace Sysborg\FocusNfe\app\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EmpresaDTO extends DTO
{
  public function __construct(
    public string $nome,
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
    return new self(
        $data['razao_social'],
        $data['nome_fantasia'] ?? '',
        $data['bairro'],
        $data['cep'],
        $data['cnpj'],
        $data['complemento'] ?? '',
        $data['email'],
        $data['inscricao_estadual'] ?? '',
        $data['inscricao_municipal'] ?? '',
        $data['logradouro'],
        $data['numero'],
        $data['regime_tributario'],
        $data['telefone'],
        $data['municipio'],
        $data['uf'],
        $data['habilita_nfe'],
        $data['habilita_nfce'],
        $data['arquivo_certificado'] ?? '',
        $data['senha_certificado'] ?? '',
        $data['csc_nfce_producao'] ?? '',
        $data['id_token_nfce_producao'] ?? '',
        $data['envia_email_destinatario'],
        $data['discrimina_imposto'],
    );
  }
}
