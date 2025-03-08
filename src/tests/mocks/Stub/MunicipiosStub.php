<?php

namespace Sysborg\FocusNFe\tests\mocks\Stub;

class MunicipioStub
{
    /**
     * Retorna um exemplo de resposta para a consulta de municípios
     *
     * @return array
     */
    public static function consultaMunicipios(): array
    {
        return [
            [
                "codigo_municipio" => "4204806",
                "nome_municipio" => "Curitibanos",
                "sigla_uf" => "SC",
                "nome_uf" => "Santa Catarina",
                "nfse_habilitada" => false
            ],
            [
                "codigo_municipio" => "4106902",
                "nome_municipio" => "Curitiba",
                "sigla_uf" => "PR",
                "nome_uf" => "Paraná",
                "nfse_habilitada" => true,
                "requer_certificado_nfse" => true,
                "possui_ambiente_homologacao_nfse" => true,
                "possui_cancelamento_nfse" => true,
                "provedor_nfse" => "Tecnos",
                "endereco_obrigatorio_nfse" => null,
                "cpf_cnpj_obrigatorio_nfse" => null,
                "codigo_cnae_obrigatorio_nfse" => true,
                "item_lista_servico_obrigatorio_nfse" => false,
                "codigo_tributario_municipio_obrigatorio_nfse" => false
            ]
        ];
    }

    /**
     * Retorna um exemplo de resposta para a consulta da lista de serviços de um município
     *
     * @return array
     */
    public static function consultaListaServico(): array
    {
        return [
            [
                "codigo" => "1.06",
                "descricao" => "Assessoria e consultoria em informática.",
                "tax_rate" => null
            ],
            [
                "codigo" => "1.07",
                "descricao" => "Suporte técnico em informática, inclusive instalação, configuração e manutenção de programas de computação e bancos de dados.",
                "tax_rate" => null
            ]
        ];
    }
}
