<?php

namespace Sysborg\FocusNFe\tests\mocks\Stub;

class CFOPStub {
    
    /**
     * Retorna um stub de sucesso ao buscar um CFOP pelo código.
     *
     * @return string
     */
    public static function sucesso(): string
    {
        return json_encode([
            "codigo" => "2151",
            "descricao" => "2151 - Transferência p/ industrialização ou produção rural"
        ]);
    }

    /**
     * Retorna um stub para erro de CFOP não encontrado.
     *
     * @return string
     */
    public static function naoEncontrado(): string
    {
        return json_encode([
            "codigo" => "nao_encontrado",
            "mensagem" => "Código CFOP não encontrado"
        ]);
    }

    /**
     * Retorna um stub de erro na requisição.
     *
     * @return string
     */
    public static function requisicaoInvalida(): string
    {
        return json_encode([
            "codigo" => "requisicao_invalida",
            "mensagem" => "Parâmetro 'codigo' inválido ou ausente"
        ]);
    }

    /**
     * Retorna um stub para a listagem de CFOPs com paginação.
     *
     * @return string
     */
    public static function listaCFOPs(): string
    {
        return json_encode([
            "cfops" => [
                [
                    "codigo" => "2151",
                    "descricao" => "2151 - Transferência p/ industrialização ou produção rural"
                ],
                [
                    "codigo" => "2152",
                    "descricao" => "2152 - Transferência p/ comercialização"
                ],
                [
                    "codigo" => "2153",
                    "descricao" => "2153 - Transferência de energia elétrica p/ distribuição"
                ],
                [
                    "codigo" => "2154",
                    "descricao" => "2154 - Transferência p/ utilização na prestação de serviço"
                ],
                [
                    "codigo" => "2159",
                    "descricao" => "2159 - Entrada decorrente do fornecimento de produto ou mercadoria de ato cooperativo"
                ],
                [
                    "codigo" => "2201",
                    "descricao" => "2201 - Devolução de venda de produção do estabelecimento"
                ],
                [
                    "codigo" => "2202",
                    "descricao" => "2202 - Devolução de venda de mercadoria adquirida ou recebida de terceiros"
                ]
            ],
            "total" => 7,
            "offset" => 1
        ]);
    }
}
