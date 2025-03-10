<?php

namespace Sysborg\FocusNFe\tests\mocks\Stub;

class CTeStub
{

      /**
     * Mock de request para a emissão de CTe.
     * 
     * @return array
     */
    public static function request(): array
    {
        return [
            'modal_aereo' => [
                'numero_minuta' => '000001234',
                'numero_operacional' => '12345678901',
                'data_prevista_entrega' => '2018-01-01',
                'dimensao_carga' => '1234X1234X1234',
                'informacoes_manuseio' => '03',
                'classe_tarifa' => 'G',
                'codigo_tarifa' => '123',
                'valor_tarifa' => '123.00',
            ],
            'cfop' => '5353',
            'natureza_operacao' => 'PREST. DE SERV. TRANSPORTE A ESTAB. COMERCIAL',
            'data_emissao' => '2018-05-17T11:13:04-03:00',
            'tipo_documento' => 0,
            'codigo_municipio_envio' => '2927408',
            'municipio_envio' => 'Salvador',
            'uf_envio' => 'BA',
            'codigo_municipio_inicio' => 2927408,
            'tipo_servico' => 0,
            'municipio_inicio' => 'Salvador',
            'uf_inicio' => 'BA',
            'codigo_municipio_fim' => '2927408',
            'municipio_fim' => 'Salvador',
            'uf_fim' => 'BA',
            'retirar_mercadoria' => '0',
            'detalhes_retirar' => 'Teste detalhes retirar',
            'indicador_inscricao_estadual_tomador' => '9',
            'tomador' => '3',
            'caracterisca_adicional_transporte' => 'c.adic.transp.',
            'caracterisca_adicional_servico' => 'Teste caract add servico',
            'funcionario_emissor' => 'func.emiss',
            'codigo_interno_origem' => 'Teste codigo interno origem',
            'codigo_interno_passagem' => 'codIntPass',
            'codigo_interno_destino' => 'Teste codigo interno destino',
            'codigo_rota' => 'cod rota',
            'tipo_programacao_entrega' => '0',
            'sem_hora_tipo_hora_programada' => '0',
            'cnpj_emitente' => '11111451000111',
            'cpf_remetente' => '08111727908',
            'nome_remetente' => 'CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL',
            'telefone_remetente' => '7734629600',
            'logradouro_remetente' => 'R. XYZ',
            'numero_remetente' => '1205',
            'bairro_remetente' => 'Vila Perneta',
            'codigo_municipio_remetente' => '4119152',
            'municipio_remetente' => 'Pinhais',
            'uf_remetente' => 'PR',
            'cep_remetente' => '83124310',
            'codigo_pais_remetente' => '1058',
            'pais_remetente' => 'Brasil',
            'cnpj_destinatario' => '00112222000149',
            'inscricao_estadual_destinatario' => '02220020926081',
            'nome_destinatario' => 'CT-E EMITIDO EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL',
            'telefone_destinatario' => '7333332600',
            'logradouro_destinatario' => 'R. Alto Parana',
            'numero_destinatario' => '190',
            'bairro_destinatario' => 'Sao Cristovao',
            'codigo_municipio_destinatario' => '2927408',
            'municipio_destinatario' => 'Salvador',
            'uf_destinatario' => 'BA',
            'cep_destinatario' => '83222380',
            'codigo_pais_destinatario' => '1058',
            'pais_destinatario' => 'Brasil',
            'email_destinatario' => 'fiscal@example.com',
            'valor_total' => '1500.00',
            'valor_receber' => '750.00',
            'icms_situacao_tributaria' => '00',
            'icms_base_calculo' => '50635.27',
            'icms_aliquota' => '17.00',
            'icms_valor' => '8608.00',
            'valor_total_carga' => '200000.00',
            'produto_predominante' => 'teste produto carga',
            'outras_caracteristicas_carga' => 'teste caracteristicas carga',
            'quantidades' => [
                [
                    'codigo_unidade_medida' => '01',
                    'tipo_medida' => 'PESO BRUTO',
                    'quantidade' => '1.0000',
                ],
                [
                    'codigo_unidade_medida' => '02',
                    'tipo_medida' => 'PESO BRUTO',
                    'quantidade' => '2.0000',
                ],
            ],
            'valor_carga_averbacao' => '200000.00',
            'nfes' => [
                [
                    'chave_nfe' => '35122225222278000855550010000002821510931504',
                    'pin_suframa' => '1234',
                    'data_prevista' => '2018-05-07',
                ],
            ],
            'valor_original_fatura' => '12000.00',
            'valor_desconto_fatura' => '1000.00',
            'valor_liquido_fatura' => '11000.00',
            'duplicatas' => [
                [
                    'data_vencimento' => '2018-05-07',
                    'valor' => '13000.00',
                ],
            ],
        ];
    }

 /**
     * Retorna um exemplo de erro por requisição inválida 
     *
     * @return array
     */
    public static function erroRequisicaoInvalida(): array
    {
        return [
            "codigo" => "requisicao_invalida",
            "mensagem" => "Faltou informar algum campo na requisição. Este campo é informado na mensagem do erro."
        ];
    }

    /**
     * Retorna um exemplo de resposta para a consulta do CT-e autorizado 
     *
     * @return array
     */
    public static function consultaCTeSucesso(): array
    {
        return [
            "cnpj_emitente" => "11111151000119",
            "ref" => "ref123",
            "status" => "autorizado",
            "status_sefaz" => "100",
            "mensagem_sefaz" => "Autorizado o uso do CT-e",
            "chave" => "CTe2111111461115100011957001000000111973476363",
            "numero" => "11",
            "serie" => "1",
            "modelo" => "57",
            "caminho_xml" => "https://focusnfe.s3-sa-east-1.amazonaws.com/arquivos_development/11111151000119/201805/XMLs/311110000007009_v03.00-protCTe.xml",
            "caminho_xml_carta_correcao" => "https://focusnfe.s3-sa-east-1.amazonaws.com/arquivos_development/11111151000119/201805/XMLs/311110000007012_v03.00-eventoCTe.xml"
        ];
    }

    /**
     * Retorna um exemplo de resposta para a requisição de cancelamento do CT-e 
     *
     * @return array
     */
    public static function cancelamentoCTeSucesso(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado ao CT-e",
            "status" => "cancelado",
            "caminho_xml" => "https://focusnfe.s3-sa-east-1.amazonaws.com/arquivos_development/14674451000119/201805/XMLs/329180000006929_v03.00-eventoCTe.xml"
        ];
    }

    /**
     * Retorna um exemplo de resposta para a Carta de Correção Eletrônica (CC-e) do CT-e 
     *
     * @return array
     */
    public static function cartaCorrecaoCTeSucesso(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado ao CT-e",
            "status" => "autorizado",
            "caminho_xml" => "https://focusnfe.s3-sa-east-1.amazonaws.com/arquivos_development/11111151000119/201805/XMLs/321110000006913_v03.00-eventoCTe.xml",
            "numero_carta_correcao" => 2
        ];
    }
}
