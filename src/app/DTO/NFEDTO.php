<?php

namespace Sysborg\FocusNFe\app\DTO;

use Carbon\Carbon;

class NFeDTO extends DTO {
    public function __construct(
        public string $naturezaOperacao,
        public Carbon $dataEmissao,
        public int $tipoDocumento,
        public int $localDestino,
        public int $finalidadeEmissao,
        public int $consumidorFinal,
        public int $presencaComprador,
        public string $cnpjEmitente,
        public string $cpfEmitente,
        public string $inscricaoEstadualEmitente,
        public string $logradouroEmitente,
        public string $numeroEmitente,
        public string $bairroEmitente,
        public string $municipioEmitente,
        public string $ufEmitente,
        public int $regimeTributarioEmitente,
        public string $nomeDestinatario,
        public ?string $cnpjDestinatario,
        public ?string $cpfDestinatario,
        public ?string $inscricaoEstadualDestinatario,
        public string $logradouroDestinatario,
        public string $numeroDestinatario,
        public string $bairroDestinatario,
        public string $municipioDestinatario,
        public string $ufDestinatario,
        public int $indicadorInscricaoEstadualDestinatario,
        public array $itens
    ) {}

 
    public static function fromArray(array $data): self
    {
        return new self(
            $data['natureza_operacao'],
            new Carbon($data['data_emissao']),
            $data['tipo_documento'],
            $data['local_destino'],
            $data['finalidade_emissao'],
            $data['consumidor_final'],
            $data['presenca_comprador'],
            $data['cnpj_emitente'],
            $data['cpf_emitente'] ?? '',
            $data['inscricao_estadual_emitente'],
            $data['logradouro_emitente'],
            $data['numero_emitente'],
            $data['bairro_emitente'],
            $data['municipio_emitente'],
            $data['uf_emitente'],
            $data['regime_tributario_emitente'],
            $data['nome_destinatario'],
            $data['cnpj_destinatario'] ?? null,
            $data['cpf_destinatario'] ?? null,
            $data['inscricao_estadual_destinatario'] ?? null,
            $data['logradouro_destinatario'],
            $data['numero_destinatario'],
            $data['bairro_destinatario'],
            $data['municipio_destinatario'],
            $data['uf_destinatario'],
            $data['indicador_inscricao_estadual_destinatario'],
            $data['itens']
        );
    }
}
