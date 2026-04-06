<?php

namespace Sysborg\FocusNfe\app\DTO;

class NFSeNDTO extends DTO
{
    public function __construct(
        // Identificação
        public string $dataEmissao,
        public string $dataCompetencia,
        public int $codigoMunicipioEmissora,
        // Prestador
        public string $cnpjPrestador,
        public string $inscricaoMunicipalPrestador,
        public int $codigoOpcaoSimplesNacional,
        public int $regimeEspecialTributacao,
        // Tomador
        public string $cnpjTomador,
        public string $razaoSocialTomador,
        public int $codigoMunicipioTomador,
        // Serviço
        public int $codigoMunicipioPrestacao,
        public string $codigoTributacaoNacionalIss,
        public string $descricaoServico,
        public float $valorServico,
        public int $tributacaoIss,
        public int $tipoRetencaoIss,
        // Tomador — campos opcionais
        public ?string $cpfTomador = null,
        public ?string $cepTomador = null,
        public ?string $logradouroTomador = null,
        public ?string $numeroTomador = null,
        public ?string $complementoTomador = null,
        public ?string $bairroTomador = null,
        public ?string $telefoneTomador = null,
        public ?string $emailTomador = null,
        // Campos adicionais opcionais
        public ?string $cnpjTomadorEstrangeiro = null,
        public ?string $nifTomadorEstrangeiro = null,
    ) {
    }

    /**
     * Cria uma instância de NFSeNDTO a partir de um array (aceita camelCase ou snake_case)
     */
    public static function fromArray(array $data): self
    {
        return new self(
            dataEmissao: $data['dataEmissao'] ?? $data['data_emissao'],
            dataCompetencia: $data['dataCompetencia'] ?? $data['data_competencia'],
            codigoMunicipioEmissora: (int) ($data['codigoMunicipioEmissora'] ?? $data['codigo_municipio_emissora']),
            cnpjPrestador: $data['cnpjPrestador'] ?? $data['cnpj_prestador'],
            inscricaoMunicipalPrestador: $data['inscricaoMunicipalPrestador'] ?? $data['inscricao_municipal_prestador'],
            codigoOpcaoSimplesNacional: (int) ($data['codigoOpcaoSimplesNacional'] ?? $data['codigo_opcao_simples_nacional']),
            regimeEspecialTributacao: (int) ($data['regimeEspecialTributacao'] ?? $data['regime_especial_tributacao']),
            cnpjTomador: $data['cnpjTomador'] ?? $data['cnpj_tomador'],
            razaoSocialTomador: $data['razaoSocialTomador'] ?? $data['razao_social_tomador'],
            codigoMunicipioTomador: (int) ($data['codigoMunicipioTomador'] ?? $data['codigo_municipio_tomador']),
            codigoMunicipioPrestacao: (int) ($data['codigoMunicipioPrestacao'] ?? $data['codigo_municipio_prestacao']),
            codigoTributacaoNacionalIss: $data['codigoTributacaoNacionalIss'] ?? $data['codigo_tributacao_nacional_iss'],
            descricaoServico: $data['descricaoServico'] ?? $data['descricao_servico'],
            valorServico: (float) ($data['valorServico'] ?? $data['valor_servico']),
            tributacaoIss: (int) ($data['tributacaoIss'] ?? $data['tributacao_iss']),
            tipoRetencaoIss: (int) ($data['tipoRetencaoIss'] ?? $data['tipo_retencao_iss']),
            cpfTomador: $data['cpfTomador'] ?? $data['cpf_tomador'] ?? null,
            cepTomador: $data['cepTomador'] ?? $data['cep_tomador'] ?? null,
            logradouroTomador: $data['logradouroTomador'] ?? $data['logradouro_tomador'] ?? null,
            numeroTomador: $data['numeroTomador'] ?? $data['numero_tomador'] ?? null,
            complementoTomador: $data['complementoTomador'] ?? $data['complemento_tomador'] ?? null,
            bairroTomador: $data['bairroTomador'] ?? $data['bairro_tomador'] ?? null,
            telefoneTomador: $data['telefoneTomador'] ?? $data['telefone_tomador'] ?? null,
            emailTomador: $data['emailTomador'] ?? $data['email_tomador'] ?? null,
            cnpjTomadorEstrangeiro: $data['cnpjTomadorEstrangeiro'] ?? $data['cnpj_tomador_estrangeiro'] ?? null,
            nifTomadorEstrangeiro: $data['nifTomadorEstrangeiro'] ?? $data['nif_tomador_estrangeiro'] ?? null,
        );
    }
}
