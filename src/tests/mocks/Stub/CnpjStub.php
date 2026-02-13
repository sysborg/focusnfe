<?php

namespace Sysborg\FocusNfe\tests\mocks\Stub;

class CnpjStub {
    /**
     * Retorna dados mocados de um CNPJ válido.
     * 
     * @return string
     */
    public static function sucesso(): string
    {
        return json_encode([
            "razao_social" => "ACRAS TECNOLOGIA DA INFORMACAO LTDA",
            "cnpj" => "07504505000132",
            "situacao_cadastral" => "ativa",
            "cnae_principal" => "620100",
            "optante_simples_nacional" => false,
            "optante_mei" => false,
            "endereco" => [
                "codigo_municipio" => "7535",
                "codigo_siafi" => "7535",
                "codigo_ibge" => "4106902",
                "nome_municipio" => "CURITIBA",
                "logradouro" => "XV DE NOVEMBRO",
                "complemento" => "Conj 602 Andar 06 Cond Eugenia Campos Ed",
                "numero" => "1234",
                "bairro" => "CENTRO",
                "cep" => "80060000",
                "uf" => "PR"
            ]
        ]);
    }

    /**
     * Retorna dados mocados de erro para CNPJ não encontrado.
     * 
     * @return string
     */
    public static function erroCnpjNaoEncontrado(): string
    {
        return json_encode([
            "codigo" => "nao_encontrado",
            "mensagem" => "CNPJ não encontrado na base de dados."
        ]);
    }

}
