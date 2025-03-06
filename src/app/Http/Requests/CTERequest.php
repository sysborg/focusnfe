<?php

namespace Sysborg\FocusNFe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @doc https://focusnfe.com.br/doc/#cte-e-cte-os_urls
 */

class CTeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'modal_aereo' => 'sometimes|array',
            'modal_aereo.numero_minuta' => 'required|string|regex:/^\d{9}$/',
            'modal_aereo.numero_operacional' => 'required|string|regex:/^\d{11}$/',
            'modal_aereo.data_prevista_entrega' => 'required|date|after_or_equal:today',
            'modal_aereo.dimensao_carga' => 'required|string|min:5|max:14',
            'modal_aereo.informacoes_manuseio' => 'required|array',
            'modal_aereo.informacoes_manuseio.*' => 'required|string|min:2|max:2|in:01,02,03,04,05,06,07,08,09,10,11,12,13,14,15,99'
            'modal_aereo.classe_tarifa' => 'required|string|in:M,G,E',
            'modal_aereo.codigo_tarifa' => 'required|string|min:1|max:4',
            'modal_aereo.valor_tarifa' => 'required|numeric',
            'modal_aereo.artigos_perigosos' => 'sometimes|array',
            'modal_aereo.artigos_perigosos.*.numero_onu' => 'required|string|min:4|max:4',
            'modal_aereo.artigos_perigosos.*.quantidade_total_volumes' => 'required|string|min:1|max:20',
            'modal_aereo.artigos_perigosos.*.quantidade_total_artigos' => 'required|numeric',
            'modal_aereo.artigos_perigosos.*.unidade_medida' => 'sometimes|string|in:KG,KG G,LITROS,TI,Unidades',
            
            'modal_aquaviario' => 'sometimes|array',
            'modal_aquaviario.valor_prestacao_servico' => 'required|numeric|decimal:13,2',
            'modal_aquaviario.adicional_frete_renovacao_marinha' => 'required|numeric|decimal:13,2',
            'modal_aquaviario.identificacao_navio' => 'required|string|min:1|max:60',
            'modal_aquaviario.balsas' => 'sometimes|array|max:3',
            'modal_aquaviario.balsas.*.identificador' => 'required|string|min:1|max:60',
            'modal_aquaviario.numero_viagem' => 'required|string|regex:/^\d{1,10}$/',
            'modal_aquaviario.direcao' => 'required|string|min:1|max:1|in:N,S,L,O',
            'modal_aquaviario.irin_navio' => 'required|string|min:1|max:10',
            'modal_aquaviario.containers' => 'sometimes|array',
            'modal_aquaviario.containers.*.identificacao' => 'required|string|min:1|max:20',
            'modal_aquaviario.containers.*.lacres' => 'sometimes|array|max:3',
            'modal_aquaviario.containers.*.lacres.*.lacre' => 'required|string|min:1|max:20',
            'modal_aquaviario.containers.*.nfs' => 'sometimes|array',
            'modal_aquaviario.containers.*.nfs.*.serie' => 'required|string|min:1|max:3',
            'modal_aquaviario.containers.*.nfs.*.numero_documento' => 'required|string|min:1|max:20',
            'modal_aquaviario.containers.*.nfs.*.unidade_medida_rateada' => 'required|numeric|decimal:3,2',
            'modal_aquaviario.containers.*.nfes' => 'sometimes|array',
            'modal_aquaviario.containers.*.nfes.*.chave_nfe' => 'required|string|min:44|max:44',
            'modal_aquaviario.containers.*.nfes.*.unidade_medida_rateada' => 'required|numeric|decimal:3,2',
            'modal_aquaviario.tipo_navegacao' => 'required|string|min:1|max:1|in:0,1'

            'modal_ferroviario' => 'sometimes|array',
            'modal_ferroviario.tipo_trafego' => 'required|string|min:1|max:1|in:0,1,2,3',
            'modal_ferroviario.responsavel_faturamento' => 'required|string|min:1|max:1|in:1,2',
            'modal_ferroviario.ferrovia_emitente' => 'required|string|min:1|max:1|in:1,2',
            'modal_ferroviario.valor_frete_trafego_mutuo' => 'required|numeric|decimal:13,2',
            'modal_ferroviario.chave_cte_ferrovia_origem' => 'required|string|min:44|max:44',
            'modal_ferroviario.ferrovias' => 'sometimes|array',
            'modal_ferroviario.ferrovias.*.cnpj' => 'required|string|min:14|max:14',
            'modal_ferroviario.ferrovias.*.codigo_interno' => 'required|string|min:1|max:10|regex:/^\d{1,10}$/',
            'modal_ferroviario.ferrovias.*.inscricao_estadual' => 'required|string|min:2|max:14',
            'modal_ferroviario.ferrovias.*.razao_social' => 'required|string|min:2|max:60',
            'modal_ferroviario.ferrovias.*.logradouro' => 'required|string|min:2|max:255',
            'modal_ferroviario.ferrovias.*.numero' => 'required|string|min:1|max:60',
            'modal_ferroviario.ferrovias.*.complemento' => 'sometimes|string|min:1|max:60',
            'modal_ferroviario.ferrovias.*.bairro' => 'required|string|min:2|max:60',
            'modal_ferroviario.ferrovias.*.codigo_municipio' => 'required|string|min:7|max:7',
            'modal_ferroviario.ferrovias.*.municipio' => 'required|string|min:2|max:60',
            'modal_ferroviario.ferrovias.*.cep' => 'required|string|min:8|max:8',
            'modal_ferroviario.ferrovias.*.uf' => 'required|string|min:2|max:2',
            'modal_ferroviario.fluxo_ferroviario' => 'required|string|min:1|max:10',

            'modal_dutoviario' => 'sometimes|array',
            'modal_dutoviario.valor_tarifa' => 'required|numeric|decimal:13,2',
            'modal_dutoviario.data_inicio' => 'required|date',
            'modal_dutoviario.data_fim' => 'required|date',

            'modal_multimodal' => 'sometimes|array',
            'modal_multimodal.numero_certificado_operador' => 'required|string|min:1|max:60',
            'modal_multimodal.indicador_negociavel' => 'required|string|min:1|max:1|in:0,1',
            'modal_multimodal.nome_seguradora' => 'required|string|min:1|max:40',
            'modal_multimodal.cnpj_seguradora' => 'required|string|min:14|max:14',
            'modal_multimodal.numero_apolice_seguro' => 'required|string|min:1|max:60',
            'modal_multimodal.numero_averbacao_seguro' => 'required|string|min:1|max:60',

            'cfop' => 'required|string|min:4|max:4',
            'natureza_operacao' => 'required|string|min:1|max:60',
            'data_emissao' => 'required|date|after_or_equal:today',
            'tipo_documento' => 'required|string|min:1|max:1|in:0,1',
            'codigo_municipio_envio' => 'required|string|min:7|max:7',
            'municipio_envio' => 'required|string|min:1|max:60',
            'uf_envio' => 'required|string|min:2|max:2',
            'codigo_municipio_inicio' => 'required|string|min:7|max:7',
            'municipio_inicio' => 'required|string|min:1|max:60',
            'uf_inicio' => 'required|string|min:2|max:2',
            'codigo_municipio_fim' => 'required|string|min:7|max:7',
            'municipio_fim' => 'required|string|min:1|max:60',
            'uf_fim' => 'required|string|min:2|max:2',
            'retirar_mercadoria' => 'required|boolean',
            'detalhes_retirar' => 'required|string|min:1|max:60',
            'indicador_inscricao_estadual_tomador' => 'required|string|min:1|max:1|in:0,1,2,9',
            'tomador' => 'required|string',
            'caracterisca_adicional_transporte' => 'required|string|min:1|max:60',
            'caracterisca_adicional_servico' => 'required|string|min:1|max:60',
            'funcionario_emissor' => 'required|string|min:1|max:60',
            'codigo_interno_origem' => 'required|string|min:1|max:60',
            'codigo_interno_passagem' => 'required|string|min:1|max:60',
            'codigo_interno_destino' => 'required|string|min:1|max:60',
            'codigo_rotas' => 'required|string|min:1|max:60',
            'tipo_programacao_entrega' => 'required|string|min:1|max:60',
            'sem_hora_tipo_hora_programada' => 'required|string|min:1|max:60',
            'cnpj_emitente' => 'required|string|min:14|max:14',
            'cpf_emitente' => 'required|string|min:11|max:11',
            'nome_remetente' => 'required|string|min:1|max:60',
            'telefone_remetente' => 'required|string|min:10|max:11',
            'logradouro_remetente' => 'required|string|min:1|max:60',
            'numero_remetente' => 'required|string|min:1|max:60',
            'bairro_remetente' => 'required|string|min:1|max:60',
            'codigo_municipio_remetente' => 'required|string|min:7|max:7',
            'municipio_remetente' => 'required|string|min:1|max:60',
            'uf_remetente' => 'required|string|min:2|max:2',
            'cep_remetente' => 'required|string|min:8|max:8',
            'codigo_pais_remetente' => 'required|string|min:4|max:4',
            'pais_remetente' => 'required|string|min:1|max:60',
            'cnpj_destinatario' => 'required|string|min:14|max:14',
            'inscricao_estadual_destinatario' => 'required|string|min:11|max:11',
            'nome_destinatario' => 'required|string|min:1|max:60',
            'telefone_destinatario' => 'required|string|min:10|max:11',
            'logradouro_destinatario' => 'required|string|min:1|max:60',
            'numero_destinatario' => 'required|string|min:1|max:60',
            'bairro_destinatario' => 'required|string|min:1|max:60',
            'codigo_municipio_destinatario' => 'required|string|min:7|max:7',
            'municipio_destinatario' => 'required|string|min:1|max:60',
            'uf_destinatario' => 'required|string|min:2|max:2',
            'cep_destinatario' => 'required|string|min:8|max:8',
            'codigo_pais_destinatario' => 'required|string|min:4|max:4',
            'pais_destinatario' => 'required|string|min:1|max:60',
            'email_destinatario' => 'required|string|min:1|max:60',
            'valor_total_frete' => 'required|numeric|decimal:13,2',
            'valor_receber' => 'required|numeric|decimal:13,2',
            'icms_situacao_tributaria' => 'required|string|min:2|max:2',
            'icms_base_calculo' => 'required|numeric|decimal:13,2',
            'icms_aliquota' => 'required|numeric|decimal:4,2',
            'icms_valor' => 'required|numeric|decimal:13,2',
            'valor_total_carga' => 'required|numeric|decimal:13,2',
            'produto_predominante' => 'required|string|min:1|max:60',
            'outras_caracteristicas_carga' => 'required|string|min:1|max:60',
            'quantidades' => 'required|array',
            'quantidades.*' => 'required|string|min:1|max:60',
            'quantidades.*.codigo_unidade_medida' => 'required|string|min:2|max:2',
            'quantidades.*.tipo_medida' => 'required|string|min:1|max:60',
            'quantidades.*.quantidade' => 'required|numeric',
            'valor_carga_averbacao' => 'required|numeric|decimal:13,2',
            'nfes' => 'required|array',
            'nfes.*.chave_nfe' => 'required|string|min:44|max:44',
            'nfes.*.pin_suframa' => 'required|string',
            'nfes.*.data_prevista' => 'required|date|after_or_equal:today',
            'valor_original_fatura' => 'required|numeric|decimal:13,2',
            'valor_desconto_fatura' => 'required|numeric|decimal:13,2',
            'valor_liquido_fatura' => 'required|numeric|decimal:13,2',
            'duplicatas' => 'required|array',
            'duplicatas.*.data_vencimento' => 'required|date',
            'duplicatas.*.valor' => 'required|numeric|decimal:13,2',
        ];
    }
}
