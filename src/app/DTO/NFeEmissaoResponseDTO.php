<?php

namespace Sysborg\FocusNfe\app\DTO;

use Illuminate\Http\Client\Response;

class NFeEmissaoResponseDTO extends DTO
{
    public const HTTP_AUTORIZADA = 201;

    public const HTTP_PROCESSANDO = 202;

    public const HTTP_REQUISICAO_INVALIDA = 400;

    public const HTTP_NAO_AUTORIZADO = 401;

    public const HTTP_FORMATO_INVALIDO = 415;

    public const HTTP_ERRO_PROCESSAMENTO = 422;

    public const RESULTADO_AUTORIZADA = 'autorizada';

    public const RESULTADO_PROCESSANDO = 'processando';

    public const RESULTADO_REQUISICAO_INVALIDA = 'requisicao_invalida';

    public const RESULTADO_NAO_AUTORIZADO = 'nao_autorizado';

    public const RESULTADO_FORMATO_INVALIDO = 'formato_invalido';

    public const RESULTADO_ERRO_PROCESSAMENTO = 'erro_processamento';

    public const RESULTADO_DESCONHECIDO = 'desconhecido';

    /**
     * @param array<int, array{codigo?: string|null, mensagem?: string|null, campo?: string|null}> $erros
     * @param array<string, mixed> $raw
     */
    public function __construct(
        public int $http_status,
        public string $resultado,
        public bool $sucesso,
        public ?string $cnpj_emitente = null,
        public ?string $ref = null,
        public ?string $status = null,
        public ?string $status_sefaz = null,
        public ?string $mensagem_sefaz = null,
        public ?string $chave_nfe = null,
        public ?string $numero = null,
        public ?string $serie = null,
        public ?string $caminho_xml_nota_fiscal = null,
        public ?string $caminho_danfe = null,
        public ?string $codigo = null,
        public ?string $mensagem = null,
        public array $erros = [],
        public array $raw = [],
        public ?string $raw_body = null,
    ) {
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();

        return self::fromArray(
            is_array($data) ? $data : [],
            $response->status(),
            $response->body()
        );
    }

    public static function fromArray(array $data, int $httpStatus, ?string $rawBody = null): self
    {
        $resultado = self::resultadoParaStatus($httpStatus);

        return new self(
            http_status: $httpStatus,
            resultado: $resultado,
            sucesso: in_array($httpStatus, [self::HTTP_AUTORIZADA, self::HTTP_PROCESSANDO], true),
            cnpj_emitente: $data['cnpj_emitente'] ?? null,
            ref: $data['ref'] ?? null,
            status: $data['status'] ?? null,
            status_sefaz: $data['status_sefaz'] ?? null,
            mensagem_sefaz: $data['mensagem_sefaz'] ?? null,
            chave_nfe: $data['chave_nfe'] ?? null,
            numero: isset($data['numero']) ? (string) $data['numero'] : null,
            serie: isset($data['serie']) ? (string) $data['serie'] : null,
            caminho_xml_nota_fiscal: $data['caminho_xml_nota_fiscal'] ?? null,
            caminho_danfe: $data['caminho_danfe'] ?? null,
            codigo: $data['codigo'] ?? null,
            mensagem: $data['mensagem'] ?? null,
            erros: isset($data['erros']) && is_array($data['erros']) ? $data['erros'] : [],
            raw: $data,
            raw_body: $rawBody,
        );
    }

    public static function resultadoParaStatus(int $httpStatus): string
    {
        return match ($httpStatus) {
            self::HTTP_AUTORIZADA => self::RESULTADO_AUTORIZADA,
            self::HTTP_PROCESSANDO => self::RESULTADO_PROCESSANDO,
            self::HTTP_REQUISICAO_INVALIDA => self::RESULTADO_REQUISICAO_INVALIDA,
            self::HTTP_NAO_AUTORIZADO => self::RESULTADO_NAO_AUTORIZADO,
            self::HTTP_FORMATO_INVALIDO => self::RESULTADO_FORMATO_INVALIDO,
            self::HTTP_ERRO_PROCESSAMENTO => self::RESULTADO_ERRO_PROCESSAMENTO,
            default => self::RESULTADO_DESCONHECIDO,
        };
    }

    public function autorizada(): bool
    {
        return $this->http_status === self::HTTP_AUTORIZADA;
    }

    public function processando(): bool
    {
        return $this->http_status === self::HTTP_PROCESSANDO;
    }

    public function requisicaoInvalida(): bool
    {
        return $this->http_status === self::HTTP_REQUISICAO_INVALIDA;
    }

    public function naoAutorizado(): bool
    {
        return $this->http_status === self::HTTP_NAO_AUTORIZADO;
    }

    public function formatoInvalido(): bool
    {
        return $this->http_status === self::HTTP_FORMATO_INVALIDO;
    }

    public function erroProcessamento(): bool
    {
        return $this->http_status === self::HTTP_ERRO_PROCESSAMENTO;
    }
}
