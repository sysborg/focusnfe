<?php

namespace Sysborg\FocusNfe\app\DTO;

class WebhookDTO extends DTO
{
    /**
     * Eventos suportados pela API FocusNFe
     */
    public const EVENTOS = [
        'nfe_autorizada',
        'nfe_cancelada',
        'nfce_autorizada',
        'nfce_cancelada',
        'nfse_autorizada',
        'nfse_cancelada',
        'cte_autorizado',
        'cte_cancelado',
        'mdfe_autorizado',
        'mdfe_cancelado',
    ];

    public function __construct(
        public string $cnpj_emitente,
        public string $url,
        public string $evento,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            cnpj_emitente: $data['cnpj_emitente'],
            url: $data['url'],
            evento: $data['evento'],
        );
    }
}
