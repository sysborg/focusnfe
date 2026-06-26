<?php

namespace Sysborg\FocusNfe\app\DTO;

use Illuminate\Http\Client\Response;

class NFSeConsultaResponseDTO extends DTO
{
    public const STATUS_PROCESSANDO_AUTORIZACAO = 'processando_autorizacao';

    public const STATUS_AUTORIZADO = 'autorizado';

    public const STATUS_CANCELADO = 'cancelado';

    public const STATUS_ERRO_AUTORIZACAO = 'erro_autorizacao';

    public const CODIGO_NAO_ENCONTRADO = 'nao_encontrado';

    /**
     * @param  array<int, array{codigo?: string|null, mensagem?: string|null, correcao?: string|null}>  $erros
     */
    public function __construct(
        public int $http_status,
        public ?string $cnpj_prestador = null,
        public ?string $ref = null,
        public ?string $numero_rps = null,
        public ?string $serie_rps = null,
        public ?string $tipo_rps = null,
        public ?string $status = null,
        public ?string $numero = null,
        public ?string $codigo_verificacao = null,
        public ?string $data_emissao = null,
        public ?string $url = null,
        public ?string $caminho_xml_nota_fiscal = null,
        public ?string $caminho_xml_cancelamento = null,
        public ?string $url_danfse = null,
        public array $erros = [],
        public ?string $codigo = null,
        public ?string $mensagem = null,
    ) {}

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray(is_array($data) ? $data : [], $response->status());
    }

    public static function fromArray(array $data, int $httpStatus = 200): self
    {
        return new self(
            http_status: $httpStatus,
            cnpj_prestador: $data['cnpj_prestador'] ?? null,
            ref: $data['ref'] ?? null,
            numero_rps: $data['numero_rps'] ?? null,
            serie_rps: $data['serie_rps'] ?? null,
            tipo_rps: $data['tipo_rps'] ?? null,
            status: $data['status'] ?? null,
            numero: $data['numero'] ?? null,
            codigo_verificacao: $data['codigo_verificacao'] ?? null,
            data_emissao: $data['data_emissao'] ?? null,
            url: $data['url'] ?? null,
            caminho_xml_nota_fiscal: $data['caminho_xml_nota_fiscal'] ?? null,
            caminho_xml_cancelamento: $data['caminho_xml_cancelamento'] ?? null,
            url_danfse: $data['url_danfse'] ?? null,
            erros: isset($data['erros']) && is_array($data['erros']) ? $data['erros'] : [],
            codigo: $data['codigo'] ?? null,
            mensagem: $data['mensagem'] ?? null,
        );
    }

    public function processandoAutorizacao(): bool
    {
        return $this->status === self::STATUS_PROCESSANDO_AUTORIZACAO;
    }

    public function autorizada(): bool
    {
        return $this->status === self::STATUS_AUTORIZADO;
    }

    public function cancelada(): bool
    {
        return $this->status === self::STATUS_CANCELADO;
    }

    public function erroAutorizacao(): bool
    {
        return $this->status === self::STATUS_ERRO_AUTORIZACAO;
    }

    public function naoEncontrada(): bool
    {
        return $this->http_status === 404 || $this->codigo === self::CODIGO_NAO_ENCONTRADO;
    }
}
