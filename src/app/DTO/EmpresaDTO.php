<?php
namespace Sysborg\FocusNFe\App\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EmpresaDTO extends DTO {
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
    public string $arquivoCertificado,
    public string $senhaCertificado,
    public string $cscNfceProducao,
    public string $idTokenNfceProducao,
    public bool $enviaEmailDestinatario = true,
    public bool $discriminaImposto = true,
  ) {}

  /**
   * Cria um objeto EmpresaDTO a partir de um array
   * 
   * @param array $data
   * @return EmpresaDTO
   */
  public static function fromArray (array $data): self
  {
    return new self(
      $validatedData['razaoSocial'],
      $validatedData['nomeFantasia'],
      $validatedData['bairro'],
      $validatedData['cep'],
      $validatedData['cnpj'],
      $validatedData['complemento'],
      $validatedData['email'],
      $validatedData['inscricaoEstadual'],
      $validatedData['inscricaoMunicipal'],
      $validatedData['logradouro'],
      $validatedData['numero'],
      $validatedData['regimeTributario'],
      $validatedData['telefone'],
      $validatedData['municipio'],
      $validatedData['uf'],
      $validatedData['habilitaNfe'],
      $validatedData['habilitaNfce'],
      $validatedData['arquivoCertificado'],
      $validatedData['senhaCertificado'],
      $validatedData['cscNfceProducao'],
      $validatedData['idTokenNfceProducao'],
      $validatedData['enviaEmailDestinatario'],
      $validatedData['discriminaImposto'],
    );
  }
}
