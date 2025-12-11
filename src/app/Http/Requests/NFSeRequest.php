<?php

namespace Sysborg\FocusNfe\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Sysborg\FocusNfe\app\DTO\NFSeDTO;
use Sysborg\FocusNfe\app\DTO\PrestadorDTO;
use Sysborg\FocusNfe\app\DTO\TomadorDTO;
use Sysborg\FocusNfe\app\DTO\ServicoDTO;
use Sysborg\FocusNfe\app\DTO\EnderecoDTO;

class NFSeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(
            NFSeDTO::rules(),
            $this->getPrestadorRules(),
            $this->getTomadorRules(),
            $this->getEnderecoRules(),
            $this->getServicoRules()
        );
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return array_merge(
            NFSeDTO::messages(),
            $this->getPrestadorMessages(),
            $this->getTomadorMessages(),
            $this->getEnderecoMessages(),
            $this->getServicoMessages()
        );
    }

    /**
     * Regras de validação para Prestador
     *
     * @return array
     */
    private function getPrestadorRules(): array
    {
        $rules = PrestadorDTO::rules();
        return [
            'prestador.cnpj' => $rules['cnpj'],
            'prestador.inscricaoMunicipal' => $rules['inscricaoMunicipal'],
            'prestador.codigoMunicipio' => $rules['codigoMunicipio'],
        ];
    }

    /**
     * Mensagens de validação para Prestador
     *
     * @return array
     */
    private function getPrestadorMessages(): array
    {
        $messages = PrestadorDTO::messages();
        return [
            'prestador.cnpj.required' => $messages['cnpj.required'],
            'prestador.cnpj.string' => $messages['cnpj.string'],
            'prestador.cnpj.cnpj' => $messages['cnpj.cnpj'],
            'prestador.inscricaoMunicipal.required' => $messages['inscricaoMunicipal.required'],
            'prestador.inscricaoMunicipal.string' => $messages['inscricaoMunicipal.string'],
            'prestador.inscricaoMunicipal.max' => $messages['inscricaoMunicipal.max'],
            'prestador.codigoMunicipio.required' => $messages['codigoMunicipio.required'],
            'prestador.codigoMunicipio.string' => $messages['codigoMunicipio.string'],
            'prestador.codigoMunicipio.max' => $messages['codigoMunicipio.max'],
        ];
    }

    /**
     * Regras de validação para Tomador
     *
     * @return array
     */
    private function getTomadorRules(): array
    {
        $rules = TomadorDTO::rules();
        return [
            'tomador.cnpj' => $rules['cnpj'],
            'tomador.razaoSocial' => $rules['razaoSocial'],
            'tomador.email' => $rules['email'],
        ];
    }

    /**
     * Mensagens de validação para Tomador
     *
     * @return array
     */
    private function getTomadorMessages(): array
    {
        $messages = TomadorDTO::messages();
        return [
            'tomador.cnpj.required' => $messages['cnpj.required'],
            'tomador.cnpj.string' => $messages['cnpj.string'],
            'tomador.cnpj.cnpj' => $messages['cnpj.cnpj'],
            'tomador.razaoSocial.required' => $messages['razaoSocial.required'],
            'tomador.razaoSocial.string' => $messages['razaoSocial.string'],
            'tomador.razaoSocial.max' => $messages['razaoSocial.max'],
            'tomador.email.required' => $messages['email.required'],
            'tomador.email.email' => $messages['email.email'],
            'tomador.email.max' => $messages['email.max'],
        ];
    }

    /**
     * Regras de validação para Endereço
     *
     * @return array
     */
    private function getEnderecoRules(): array
    {
        $rules = EnderecoDTO::rules();
        return [
            'tomador.endereco.logradouro' => $rules['logradouro'],
            'tomador.endereco.numero' => $rules['numero'],
            'tomador.endereco.complemento' => $rules['complemento'],
            'tomador.endereco.bairro' => $rules['bairro'],
            'tomador.endereco.codigoMunicipio' => $rules['codigoMunicipio'],
            'tomador.endereco.uf' => $rules['uf'],
            'tomador.endereco.cep' => $rules['cep'],
        ];
    }

    /**
     * Mensagens de validação para Endereço
     *
     * @return array
     */
    private function getEnderecoMessages(): array
    {
        $messages = EnderecoDTO::messages();
        return [
            'tomador.endereco.logradouro.required' => $messages['logradouro.required'],
            'tomador.endereco.logradouro.string' => $messages['logradouro.string'],
            'tomador.endereco.logradouro.max' => $messages['logradouro.max'],
            'tomador.endereco.numero.required' => $messages['numero.required'],
            'tomador.endereco.numero.string' => $messages['numero.string'],
            'tomador.endereco.numero.max' => $messages['numero.max'],
            'tomador.endereco.complemento.string' => $messages['complemento.string'],
            'tomador.endereco.complemento.max' => $messages['complemento.max'],
            'tomador.endereco.bairro.required' => $messages['bairro.required'],
            'tomador.endereco.bairro.string' => $messages['bairro.string'],
            'tomador.endereco.bairro.max' => $messages['bairro.max'],
            'tomador.endereco.codigoMunicipio.required' => $messages['codigoMunicipio.required'],
            'tomador.endereco.codigoMunicipio.string' => $messages['codigoMunicipio.string'],
            'tomador.endereco.codigoMunicipio.max' => $messages['codigoMunicipio.max'],
            'tomador.endereco.uf.required' => $messages['uf.required'],
            'tomador.endereco.uf.string' => $messages['uf.string'],
            'tomador.endereco.uf.size' => $messages['uf.size'],
            'tomador.endereco.cep.required' => $messages['cep.required'],
            'tomador.endereco.cep.string' => $messages['cep.string'],
            'tomador.endereco.cep.max' => $messages['cep.max'],
        ];
    }

    /**
     * Regras de validação para Serviço
     *
     * @return array
     */
    private function getServicoRules(): array
    {
        $rules = ServicoDTO::rules();
        return [
            'servico.aliquota' => $rules['aliquota'],
            'servico.discriminacao' => $rules['discriminacao'],
            'servico.issRetido' => $rules['issRetido'],
            'servico.itemListaServico' => $rules['itemListaServico'],
            'servico.codigoTributarioMunicipio' => $rules['codigoTributarioMunicipio'],
            'servico.valorServicos' => $rules['valorServicos'],
            'servico.codigoCnae' => $rules['codigoCnae'],
        ];
    }

    /**
     * Mensagens de validação para Serviço
     *
     * @return array
     */
    private function getServicoMessages(): array
    {
        $messages = ServicoDTO::messages();
        return [
            'servico.aliquota.required' => $messages['aliquota.required'],
            'servico.aliquota.numeric' => $messages['aliquota.numeric'],
            'servico.aliquota.min' => $messages['aliquota.min'],
            'servico.aliquota.max' => $messages['aliquota.max'],
            'servico.discriminacao.required' => $messages['discriminacao.required'],
            'servico.discriminacao.string' => $messages['discriminacao.string'],
            'servico.issRetido.required' => $messages['issRetido.required'],
            'servico.issRetido.boolean' => $messages['issRetido.boolean'],
            'servico.itemListaServico.required' => $messages['itemListaServico.required'],
            'servico.itemListaServico.string' => $messages['itemListaServico.string'],
            'servico.itemListaServico.max' => $messages['itemListaServico.max'],
            'servico.codigoTributarioMunicipio.required' => $messages['codigoTributarioMunicipio.required'],
            'servico.codigoTributarioMunicipio.string' => $messages['codigoTributarioMunicipio.string'],
            'servico.codigoTributarioMunicipio.max' => $messages['codigoTributarioMunicipio.max'],
            'servico.valorServicos.required' => $messages['valorServicos.required'],
            'servico.valorServicos.numeric' => $messages['valorServicos.numeric'],
            'servico.valorServicos.min' => $messages['valorServicos.min'],
            'servico.codigoCnae.string' => $messages['codigoCnae.string'],
            'servico.codigoCnae.max' => $messages['codigoCnae.max'],
        ];
    }
}
