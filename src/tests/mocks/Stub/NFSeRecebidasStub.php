<?php

namespace Sysborg\FocusNfe\tests\mocks\Stub;

class NFSeRecebidasStub {
    
    /**
     * Retorna uma resposta simulada de todas as NFSe recebidas para um CNPJ.
     *
     * @return string
     */
    public static function todasNfseRecebidas(): string
    {
        return json_encode([
            [
                "chave" => "NFSe859042900001504305108-5555-123456-DMMY000",
                "versao" => 846,
                "status" => "autorizado",
                "numero" => "123456",
                "numero_rps" => "789",
                "serie_rps" => "S",
                "data_emissao" => "2023-02-01T21:43:00-03:00",
                "data_emissao_rps" => "2023-02-01T21:43:00-03:00",
                "codigo_verificacao" => "DMMY000",
                "valor_servicos" => "100.00",
                "documento_prestador" => "85904290000150",
                "nome_prestador" => "Fictício Prestador",
                "inscricao_municipal_prestador" => "5555",
                "nome_municipio" => "Caxias do Sul",
                "sigla_uf" => "RS",
                "codigo_municipio" => "4305108",
                "documento_tomador" => "07504505000132",
                "url" => "https://nfse.caxias.rs.gov.br/consulta/pdf?chaveAcesso=DMMY000",
                "url_xml" => "https://focusnfe.s3.sa-east-1.amazonaws.com/arquivos/07504505000132/202302/NFSeRecebidas/NFSe859042900001504305108-5555-123456-DMMY000.xml"
            ]
        ]);
    }

    /**
     * Retorna uma resposta simulada para consulta de uma NFSe específica pela chave.
     *
     * @return string
     */
    public static function consultaNfsePorChave(): string
    {
        return json_encode([
            "chave" => "NFSe859042900001504305108-5555-123456-DMMY000",
            "versao" => 846,
            "status" => "autorizado",
            "numero" => "123456",
            "numero_rps" => "789",
            "serie_rps" => "S",
            "data_emissao" => "2023-02-01T21:43:00-03:00",
            "data_emissao_rps" => "2023-02-01T21:43:00-03:00",
            "codigo_verificacao" => "DMMY000",
            "valor_servicos" => "100.00",
            "documento_prestador" => "85904290000150",
            "nome_prestador" => "Fictício Prestador",
            "inscricao_municipal_prestador" => "5555",
            "nome_municipio" => "Caxias do Sul",
            "sigla_uf" => "RS",
            "codigo_municipio" => "4305108",
            "documento_tomador" => "07504505000132",
            "url" => "https://nfse.caxias.rs.gov.br/consulta/pdf?chaveAcesso=DMMY000",
            "url_xml" => "https://focusnfe.s3.sa-east-1.amazonaws.com/arquivos/07504505000132/202302/NFSeRecebidas/NFSe859042900001504305108-5555-123456-DMMY000.xml"
        ]);
    }

    /**
     * Retorna erro quando a chave informada não for encontrada.
     *
     * @return string
     */
    public static function erroChaveNaoEncontrada(): string
    {
        return json_encode([
            "codigo" => "nao_encontrado",
            "mensagem" => "NFSe não encontrada para a chave informada."
        ]);
    }
}
