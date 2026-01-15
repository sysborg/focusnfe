<?php

namespace Sysborg\FocusNfe\tests\mocks\Stub;

class CTERecebidasStub {

    /**
     * Retorna dados mocados da consulta de CTEs recebidas.
     * 
     * @return string
     */
    public static function consultaCTesRecebidas(): string
    {
        return json_encode([
            [
                "nome_emitente" => "Empresa emitente Ltda.",
                "documento_emitente" => "79160190000193",
                "chave" => "35191008165642000152570020004201831004201839",
                "valor_total" => "295.66",
                "data_emissao" => "2019-10-07T23:44:00-03:00",
                "situacao" => "autorizado",
                "tipo_cte" => "0",
                "versao" => 1709,
                "digest_value" => "Xa/A04zX/qSMh13ILIh1V7GTA",
                "carta_correcao" => null,
                "data_cancelamento" => null
            ]
        ]);
    }

    /**
     * Retorno de resposta para consulta individual de CTe recebida.
     * 
     * @return string
     */
    public static function consultaIndividual(): string
    {
        return json_encode([
            "nome_emitente" => "Empresa emitente Ltda.",
            "documento_emitente" => "79160190000193",
            "chave" => "35191008165642000152570020004201831004201839",
            "valor_total" => "295.66",
            "data_emissao" => "2019-10-07T23:44:00-03:00",
            "situacao" => "autorizado",
            "tipo_cte" => "0",
            "versao" => 1709,
            "digest_value" => "Xa/A04zX/qSMh13ILIh1V7GTA",
            "carta_correcao" => null,
            "data_cancelamento" => null
        ]);
    }

    /**
     * request para informar desacordo.
     * 
     * @return string
     */
    public static function informarDesacordo(): string
    {
        return json_encode([
            "observacoes" => "Observações referentes ao desacordo informado"
        ]);
    }

    /**
     * Retorno da resposta para consulta de desacordo já registrado.
     * 
     * @return string
     */
    public static function consultaDesacordo(): string
    {
        return json_encode([
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Evento registrado e vinculado a CT-e",
            "status" => "evento_registrado",
            "protocolo" => "891170005150285",
            "tipo" => "nao_realizada",
            "justificativa" => "Fornecedor cancelou a operação devido à falta dos produtos em estoque."
        ]);
    }
}