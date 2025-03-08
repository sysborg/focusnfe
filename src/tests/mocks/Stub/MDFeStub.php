<?php

namespace Sysborg\FocusNFe\tests\mocks\Stub;

class MDFeStub
{

    


    /**
     * Retorna um exemplo de resposta para a consulta de MDF-e autorizado
     *
     * @return array
     */
    public static function consultaMDFe(): array
    {
        return [
            "cnpj_emitente" => "11111151000119",
            "ref" => "ref123",
            "status" => "autorizado",
            "status_sefaz" => "100",
            "mensagem_sefaz" => "Autorizado o uso do MDF-e",
            "chave" => "MDFe21111114611151000119570010000000111973476363",
            "numero" => "11",
            "serie" => "1",
            "modelo" => "58",
            "caminho_xml" => "https://focusnfe.s3-sa-east-1.amazonaws.com/arquivos_development/11111151000119/201805/XMLs/311110000007009_v03.00-protMDFe.xml"
        ];
    }

    /**
     * Retorna um exemplo de resposta para a requisição de cancelamento de MDF-e
     *
     * @return array
     */
    public static function cancelamentoMDFe(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a MDF-e",
            "status" => "cancelado",
            "caminho_xml" => "https://focusnfe.s3-sa-east-1.amazonaws.com/arquivos_development/14674451000119/201805/XMLs/329180000006929_v03.00-eventoMDFe.xml"
        ];
    }

    /**
     * Retorna um exemplo de resposta para a requisição de inclusão de condutor
     *
     * @return array
     */
    public static function inclusaoCondutor(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a MDF-e",
            "status" => "incluido",
            "caminho_xml" => "https://focusnfe.s3-sa-east-1.amazonaws.com/arquivos_development/14674451000119/201805/XMLs/329180000006929_v03.00-eventoMDFe.xml"
        ];
    }

    /**
     * Retorna um exemplo de resposta para a requisição de inclusão de DFe
     *
     * @return array
     */
    public static function inclusaoDFe(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a MDF-e",
            "status" => "incluido",
            "caminho_xml" => "https://focusnfe.s3-sa-east-1.amazonaws.com/arquivos_development/14674451000119/201805/XMLs/329180000006929_v03.00-eventoMDFe.xml"
        ];
    }

    /**
     * Retorna um exemplo de resposta para a requisição de encerramento de MDF-e
     *
     * @return array
     */
    public static function encerramentoMDFe(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a MDF-e",
            "status" => "encerrado",
            "caminho_xml" => "https://focusnfe.s3-sa-east-1.amazonaws.com/arquivos_development/14674451000119/201805/XMLs/329180000006929_v03.00-eventoMDFe.xml"
        ];
    }
}
