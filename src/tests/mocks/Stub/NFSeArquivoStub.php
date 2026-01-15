<?php

namespace Sysborg\FocusNfe\tests\mocks\Stub;

class NFSeArquivoStub
{

    /**
     * Retorna um exemplo de resposta para a  NFSe enviada por arquivo (sucesso)
     *
     * @return array
     */
    public static function EnviaNFSeArquivoSucesso(): array
    {
        return [
            "prestador" => [
                "cnpj" => "18765499000199",
                "inscricao_municipal" => "12345",
                "codigo_municipio" => "3516200"
            ],
            "lista_nfse" => [
                [
                    "data_emissao" => "2019-03-19T12:07:26-03:00",
                    "natureza_operacao" => 1,
                    "servico" => [
                        "aliquota" => 3,
                        "discriminacao" => "Nota fiscal referente a serviços prestados",
                        "iss_retido" => "false",
                        "item_lista_servico" => "0107",
                        "codigo_tributario_municipio" => "620910000",
                        "valor_servicos" => 1.0
                    ],
                    "tomador" => [
                        "cnpj" => "07504505000132",
                        "razao_social" => "Acras Tecnologia da Informação LTDA",
                        "email" => "contato@focusnfe.com.br",
                        "endereco" => [
                            "logradouro" => "Rua Dias da Rocha Filho",
                            "numero" => "999",
                            "complemento" => "Prédio 04 - Sala 34C",
                            "bairro" => "Alto da XV",
                            "codigo_municipio" => "4106902",
                            "uf" => "PR",
                            "cep" => "80045165"
                        ]
                    ]
                ]
            ]
        ];
    }

    /**
     * Retorna um exemplo de resposta para a consulta de NFSe enviada por arquivo (lote não encontrado)
     *
     * @return array
     */
    public static function consultaNFSeArquivoErro(): array
    {
        return [
            "erro" => "Lote não encontrado com a referência informada."
        ];
    }

}
