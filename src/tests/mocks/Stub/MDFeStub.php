<?php

namespace Sysborg\FocusNFe\tests\mocks\Stub;

class MDFeStub
{


    /**
 * exemplo de request para MDFe
 *
 * @return array
 */
public static function request(): array
{
    return [
        'emitente' => 1, 
        'serie' => 1, 
        'numero' => 1001, 
        'uf_inicio' => 'SP', 
        'uf_fim' => 'RJ', 
        'cnpj_emitente' => '12345678000199', 
        'cpf_emitente' => null, 
        'inscricao_estadual_emitente' => '12345678901234', 
        'nome_emitente' => 'Transportadora Teste Ltda', 
        'nome_fantasia_emitente' => 'Transportes Teste', 
        'logradouro_emitente' => 'Av. Brasil', 
        'numero_emitente' => '500', 
        'bairro_emitente' => 'Centro', 
        'codigo_municipio_emitente' => 3550308, 
        'municipio_emitente' => 'São Paulo', 
        'uf_emitente' => 'SP', 
        'valor_total_carga' => 100000.00, 
        'codigo_unidade_medida_peso_bruto' => 1, 
    ];
}

    


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
