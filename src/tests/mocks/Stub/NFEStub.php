<?php

namespace Sysborg\FocusNfe\tests\mocks\Stub;

class NFeStub
{

   /**
     * Mock de request para a emissão de NFe.
     * 
     * @return array
     */
    public static function request(): array
    {
        return [
            'natureza_operacao' => 'Remessa',
            'data_emissao' => '2017-04-15',
            'data_entrada_saida' => '2017-04-15',
            'tipo_documento' => 1,
            'finalidade_emissao' => 1,
            'cnpj_emitente' => 'SEU_CNPJ',
            'cpf_emitente' => 'SEU_CPF',
            'nome_emitente' => 'Sua Razão Social Ltda',
            'nome_fantasia_emitente' => 'Fantasia do Emitente',
            'logradouro_emitente' => 'Rua Quinze de Abril',
            'numero_emitente' => 999,
            'bairro_emitente' => 'Jd Paulistano',
            'municipio_emitente' => 'São Paulo',
            'uf_emitente' => 'SP',
            'cep_emitente' => '01454-600',
            'inscricao_estadual_emitente' => 'SUA_INSCRICAO_ESTADUAL',
            'nome_destinatario' => 'NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL',
            'cpf_destinatario' => '03055054911',
            'inscricao_estadual_destinatario' => null,
            'telefone_destinatario' => 1196185555,
            'logradouro_destinatario' => 'Rua São Januário',
            'numero_destinatario' => 99,
            'bairro_destinatario' => 'Crespo',
            'municipio_destinatario' => 'Manaus',
            'uf_destinatario' => 'AM',
            'pais_destinatario' => 'Brasil',
            'cep_destinatario' => 69073178,
            'valor_frete' => 0.0,
            'valor_seguro' => 0,
            'valor_total' => 47.23,
            'valor_produtos' => 47.23,
            'modalidade_frete' => 0,
            'items' => [
                [
                    'numero_item' => 1,
                    'codigo_produto' => 1232,
                    'descricao' => 'Cartões de Visita',
                    'cfop' => 5923,
                    'unidade_comercial' => 'un',
                    'quantidade_comercial' => 100,
                    'valor_unitario_comercial' => 0.4723,
                    'valor_unitario_tributavel' => 0.4723,
                    'unidade_tributavel' => 'un',
                    'codigo_ncm' => 49111090,
                    'quantidade_tributavel' => 100,
                    'valor_bruto' => 47.23,
                    'icms_situacao_tributaria' => 41,
                    'icms_origem' => 0,
                    'pis_situacao_tributaria' => '07',
                    'cofins_situacao_tributaria' => '07'
                ]
            ]
        ];
    }

    /**
     * Retorna um exemplo de resposta para o envio de NFe autorizado 
     *
     * @return array
     */
    public static function envioNFeSucesso(): array
    {
        return [
            "cnpj_emitente" => "07504505000132",
            "ref" => "referencia_000899_nfe",
            "status" => "autorizado",
            "status_sefaz" => "100",
            "mensagem_sefaz" => "Autorizado o uso da NF-e",
            "chave_nfe" => "NFe4119060750450500013255001000000221923094166",
            "numero" => "22",
            "serie" => "1",
            "caminho_xml_nota_fiscal" => "/arquivos_development/07504505000132/201906/XMLs/4119060750450500013255001000000221923094166-nfe.xml",
            "caminho_danfe" => "/arquivos_development/07504505000132/201906/DANFEs/4119060750450500013255001000000221923094166.pdf"
        ];
    }

    /**
     * Retorna um exemplo de erro na validação do schema XML da NFe 
     *
     * @return array
     */
    public static function erroValidacaoSchema(): array
    {
        return [
            "codigo" => "erro_validacao_schema",
            "mensagem" => "Erro na validação do Schema XML, verifique o detalhamento dos erros",
            "erros" => [
                [
                    "mensagem" => "Preencha pelo menos um documento do destinatário: cnpj_destinatario ou cpf_destinatario",
                    "campo" => null
                ]
            ]
        ];
    }

      /**
     * Retorna um exemplo de resposta para a consulta de NFe autorizada
     *
     * @return array
     */
    public static function consultaNFeAutorizada(): array
    {
        return [
            "cnpj_emitente" => "07504505000132",
            "ref" => "referencia_000899_nfe",
            "status" => "autorizado",
            "status_sefaz" => "100",
            "mensagem_sefaz" => "Autorizado o uso da NF-e",
            "chave_nfe" => "NFe4119060750450500013255001000000221923094166",
            "numero" => "22",
            "serie" => "1",
            "caminho_xml_nota_fiscal" => "/arquivos_development/07504505000132/201906/XMLs/4119060750450500013255001000000221923094166-nfe.xml",
            "caminho_danfe" => "/arquivos_development/07504505000132/201906/DANFEs/4119060750450500013255001000000221923094166.pdf"
        ];
    }

    /**
     * Retorna um exemplo de resposta para uma NFe que ainda está sendo processada
     *
     * @return array
     */
    public static function consultaNFeProcessando(): array
    {
        return [
            "cnpj_emitente" => "07504505000132",
            "ref" => "referencia_000899_nfe",
            "status" => "processando_autorizacao"
        ];
    }

    /**
     * Retorna um exemplo de resposta para erro de autorização da NFe 
     *
     * @return array
     */
    public static function consultaNFeErroAutorizacao(): array
    {
        return [
            "cnpj_emitente" => "07504505000132",
            "ref" => "referencia_000899_nfe",
            "status" => "erro_autorizacao",
            "status_sefaz" => "598",
            "mensagem_sefaz" => "NF-e emitida em ambiente de homologacao com Razao Social do destinatario diferente de NF-E EMITIDA EM AMBIENTE DE HOMOLOGACAO - SEM VALOR FISCAL"
        ];
    }

    /**
     * Retorna um exemplo de resposta para uma NFe autorizada com CC-e anexado
     *
     * @return array
     */
    public static function consultaNFeComCCe(): array
    {
        return [
            "cnpj_emitente" => "07504505000132",
            "ref" => "referencia_000899_nfe",
            "status" => "autorizado",
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a NF-e",
            "chave_nfe" => "NFe4119060750450500013255001000000221923094166",
            "numero" => "22",
            "serie" => "1",
            "caminho_xml_nota_fiscal" => "/arquivos_development/07504505000132/201906/XMLs/4119060750450500013255001000000221923094166-nfe.xml",
            "caminho_danfe" => "/arquivos_development/07504505000132/201906/DANFEs/4119060750450500013255001000000221923094166.pdf",
            "caminho_xml_carta_correcao" => "/arquivos_development/07504505000132/201906/XMLs/4119060750450500013255001000000221923094166-cce-01.xml",
            "caminho_pdf_carta_correcao" => "/notas_fiscais/NFe4119060750450500013255001000000221923094166/cartas_correcao/1.pdf",
            "numero_carta_correcao" => 1
        ];
    }

    /**
     * Retorna um exemplo de resposta para uma NFe cancelada 
     *
     * @return array
     */
    public static function consultaNFeCancelada(): array
    {
        return [
            "cnpj_emitente" => "07504505000132",
            "ref" => "referencia_000899_nfe",
            "status" => "cancelado",
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a NF-e",
            "numero" => "22",
            "serie" => "1",
            "chave_nfe" => "NFe4119060750450500013255001000000221923094166",
            "caminho_xml_nota_fiscal" => "/arquivos_development/07504505000132/201906/XMLs/4119060750450500013255001000000221923094166-nfe.xml",
            "caminho_xml_cancelamento" => "/arquivos_development/07504505000132/201906/XMLs/4119060750450500013255001000000221923094166-can.xml",
            "caminho_xml_carta_correcao" => "/arquivos_development/07504505000132/201906/XMLs/4119060750450500013255001000000221923094166-cce-02.xml",
            "caminho_pdf_carta_correcao" => "/notas_fiscais/NFe4119060750450500013255001000000221923094166/cartas_correcao/2.pdf",
            "numero_carta_correcao" => 2
        ];
    }

    /**
     * Retorna um exemplo de resposta para o cancelamento de NFe autorizado
     *
     * @return array
     */
    public static function cancelamentoNFeSucesso(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a NF-e",
            "status" => "cancelado",
            "caminho_xml_cancelamento" => "/arquivos_development/07504505000132/201906/XMLs/4119060750450500013255001000000221923094166-can.xml"
        ];
    }

    /**
     * Retorna um exemplo de erro para uma requisição inválida no cancelamento da NFe 
     *
     * @return array
     */
    public static function erroCancelamentoNFe(): array
    {
        return [
            "codigo" => "requisicao_invalida",
            "mensagem" => "Parâmetro \"justificativa\" deve ter entre 15 e 255 caracteres"
        ];
    }

       /**
     * Retorna um exemplo de resposta para o envio da Carta de Correção (CC-e) autorizado 
     *
     * @return array
     */
    public static function cartaCorrecaoSucesso(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a NF-e",
            "status" => "autorizado",
            "caminho_xml_carta_correcao" => "/arquivos_development/07504505000132/201906/XMLs/4119060750450500013255001000000221923094166-cce-01.xml",
            "caminho_pdf_carta_correcao" => "/notas_fiscais/NFe4119060750450500013255001000000221923094166/cartas_correcao/1.pdf",
            "numero_carta_correcao" => 1
        ];
    }

     /**
     * Retorna um exemplo de resposta para a inutilização de numeração da NFe autorizada (HTTP 200)
     *
     * @return array
     */
    public static function inutilizacaoNFeSucesso(): array
    {
        return [
            "status_sefaz" => "102",
            "mensagem_sefaz" => "Inutilizacao de numero homologado",
            "serie" => "1",
            "numero_inicial" => "999",
            "numero_final" => "1000",
            "modelo" => "55",
            "cnpj" => "18075045000130",
            "status" => "autorizado",
            "caminho_xml" => "/arquivos_development/07504505000132/201906/XMLs/190750450500013255001000000999000001000-inu.xml",
            "protocolo_sefaz" => "135210002233889"
        ];
    }

    /**
     * Retorna um exemplo de erro ao tentar inutilizar numeração da NFe (HTTP 422)
     *
     * @return array
     */
    public static function inutilizacaoNFeErro(): array
    {
        return [
            "status_sefaz" => "256",
            "mensagem_sefaz" => "Uma NF-e da faixa ja esta inutilizada na Base de dados da SEFAZ",
            "serie" => "1",
            "numero_inicial" => "1000",
            "numero_final" => "1000",
            "status" => "erro_autorizacao"
        ];
    }
    
}
