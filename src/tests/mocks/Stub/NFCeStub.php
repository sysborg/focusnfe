<?php

namespace Sysborg\FocusNfe\tests\mocks\Stub;

class NFCeStub
{

/**
 * exemplo de request para a emissão de NFC-e.
 *
 * @return array
 */
public static function request(): array
{
    return [
        'natureza_operacao' => 'VENDA AO CONSUMIDOR',
        'data_emissao' => '2015-11-19T13:54:31-02:00',
        'presenca_comprador' => 1,
        'cnpj_emitente' => '05953016000132',
        'modalidade_frete' => 9,
        'local_destino' => 1,
    ];
}


      /**
     * Retorna um exemplo de resposta para o registro de conciliação financeira da NFC-e 
     *
     * @return array
     */
    public static function registraEConf(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a NF-e",
            "status" => "autorizado",
            "caminho_xml_conciliacao_financeira" => "/arquivos/12345678000123/202502/XMLs/35252023916076000150550020000030031984736315-cf-01.xml",
            "numero_conciliacao_financeira" => 1,
            "numero_protocolo" => "335250000000445"
        ];
    }

      /**
     * Retorna um exemplo de resposta para a consulta de conciliação financeira da NFC-e 
     *
     * @return array
     */
    public static function consultaEConf(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a NF-e",
            "status" => "autorizado",
            "caminho_xml_conciliacao_financeira" => "/arquivos/12345678000123/202502/XMLs/35252023916076000150550020000030031984736315-cf-01.xml",
            "numero_conciliacao_financeira" => 1,
            "numero_protocolo" => "335250000000445"
        ];
    }

       /**
     * Retorna um exemplo de resposta para o cancelamento da conciliação financeira da NFC-e 
     *
     * @return array
     */
    public static function cancelaEConf(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a NF-e",
            "status" => "autorizado",
            "caminho_xml_cancelamento_conciliacao_financeira" => "/arquivos/12345678000123/202502/XMLs/35252023916076000150550020000030041206848679-cf-canc-06.xml",
            "numero_cancelamento_conciliacao_financeira" => 6
        ];
    }

    /**
     * Retorna um exemplo de resposta para erro na consulta ou cancelamento da conciliação financeira 
     *
     * @return array
     */
    public static function erroConsultaEConf(): array
    {
        return [
            "codigo" => "object_not_found",
            "mensagem" => "A nota fiscal não possui um evento de conciliação financeira vinculado a esse protocolo."
        ];
    }

    
    /**
     * Retorna um exemplo de resposta para a consulta de NFC-e 
     *
     * @return array
     */
    public static function consultaNFCe(): array
    {
        return [
            "cnpj_emitente" => "07504505000132",
            "ref" => "07504505000132_NFCE_000001",
            "status" => "autorizado",
            "status_sefaz" => "100",
            "mensagem_sefaz" => "Autorizado o uso da NF-e",
            "chave_nfce" => "NFe4221060750450500013265001000000541799075218",
            "numero" => "524",
            "serie" => "1",
            "caminho_xml_nota_fiscal" => "/arquivos_development/07504505000132/202106/XMLs/4221060750450500013265001000000541799075218-nfce.xml",
            "caminho_danfe" => "/notas_fiscais_consumidor/NFe4221060750450500013265001000000541799075218.html",
            "qrcode_url" => "https://hom.sat.sef.sc.gov.br/nfce/consulta?p=4221060750450500013265001000000541799075218|2|1|EB75B2FF9C11198DF1093E9582AB7F1A9B08D518",
            "url_consulta_nf" => "https://hom.sat.sef.sc.gov.br/nfce/consulta"
        ];
    }

    /**
     * Retorna um exemplo de resposta para a inutilização de numeração de NFC-e 
     *
     * @return array
     */
    public static function inutilizacaoNFCe(): array
    {
        return [
            "status_sefaz" => "102",
            "mensagem_sefaz" => "Inutilizacao de numero homologado",
            "serie" => "1",
            "numero_inicial" => "999",
            "numero_final" => "1000",
            "modelo" => "65",
            "cnpj" => "180750445000132",
            "status" => "autorizado",
            "caminho_xml" => "/arquivos_development/07504505000132/201906/XMLs/190750445000132650010000009990000001000-inu.xml"
        ];
    }

    /**
     * Retorna um exemplo de resposta para erro de inutilização de NFC-e 
     *
     * @return array
     */
    public static function erroInutilizacaoNFCe(): array
    {
        return [
            "status_sefaz" => "241",
            "mensagem_sefaz" => "Um numero da faixa ja foi utilizado",
            "serie" => "1",
            "numero_inicial" => "1",
            "numero_final" => "9",
            "status" => "erro_autorizacao"
        ];
    }

     /**
     * Retorna um exemplo de resposta para o cancelamento de NFC-e autorizado 
     *
     * @return array
     */
    public static function cancelamentoNFCe(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a NFC-e",
            "status" => "cancelado",
            "numero_protocolo" => "142220000000000",
            "caminho_xml" => "/arquivos_development/07504505000132/202106/XMLs/4221060750450500013265001000000541799075218-cancel.xml"
        ];
    }

    /**
     * Retorna um exemplo de resposta para erro no cancelamento de NFC-e 
     *
     * @return array
     */
    public static function erroCancelamentoNFCe(): array
    {
        return [
            "erro" => "Cancelamento não permitido. A NFC-e já foi cancelada ou está em situação inválida."
        ];
    }

}
