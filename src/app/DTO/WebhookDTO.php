<?php

namespace Sysborg\FocusNfe\app\DTO;

class WebhookDTO extends DTO
{
    /**
     * Eventos suportados pela API FocusNFe
     */
    public const EVENTOS = [
        // NF-e
        'nfe_autorizada',
        'nfe_cancelada',
        'nfe_recebida',
        'nfe_recebida_falha_consulta',
        // NFC-e
        'nfce_autorizada',
        'nfce_cancelada',
        'nfce_contingencia',
        // NFS-e municipal
        'nfse_autorizada',
        'nfse_cancelada',
        'nfse_recebida',
        // CT-e
        'cte_autorizado',
        'cte_cancelado',
        'cte_recebida',
        // MDF-e
        'mdfe_autorizado',
        'mdfe_cancelado',
        // Outros
        'inutilizacao',
        'nfcom',
    ];

    public function __construct(
        public string $url,
        public string $evento,
        public ?string $cnpjEmitente = null,
        public ?string $cpfEmitente = null,
        public ?string $authorization = null,
        public ?string $authorizationHeader = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            url: $data['url'],
            evento: $data['evento'] ?? $data['event'],
            cnpjEmitente: $data['cnpjEmitente'] ?? $data['cnpj_emitente'] ?? null,
            cpfEmitente: $data['cpfEmitente'] ?? $data['cpf_emitente'] ?? null,
            authorization: $data['authorization'] ?? null,
            authorizationHeader: $data['authorizationHeader'] ?? $data['authorization_header'] ?? null,
        );
    }
}
