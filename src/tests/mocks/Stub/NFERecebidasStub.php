<?php

namespace Sysborg\FocusNFe\tests\mocks\Stub;

class NFeRecebidasStub
{
    /**
     * Mock de request para registrar uma manifestação de NFe recebida
     * 
     * @return array
     */
    public static function registrarManifestacaoErro(): array
    {
        return [
            "tipo" => "nao_realizada",
            "justificativa" => "Fornecedor cancelou a operação devido à falta dos produtos em estoque."
        ];
    }

   
    /**
     * Mock de resposta da consulta de manifestação de NFe recebida
     * 
     * @return array
     */
    public static function consultarManifestacao(): array
    {
        return [
            "status_sefaz" => "135",
            "mensagem_sefaz" => "Manifestação já registrada",
            "status" => "evento_registrado",
            "protocolo" => "891170005150285",
            "tipo" => "nao_realizada",
            "justificativa" => "Fornecedor cancelou a operação devido à falta dos produtos em estoque."
        ];
    }

    /**
     * Mock de erro ao registrar uma manifestação inválida
     * 
     * @return array
     */
    public static function erroManifestacao(): array
    {
        return [
            "status" => "erro",
            "status_sefaz" => "999",
            "mensagem_sefaz" => "Justificativa inválida ou não informada",
            "detalhes" => "A justificativa deve ter entre 15 e 255 caracteres."
        ];
    }
}
