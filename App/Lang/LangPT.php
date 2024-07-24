<?php

namespace App\Lang;

use App\Lang\LangInterface;

class LangPT implements LangInterface {
    public function serverError(): string {
        return 'Não foi possível atender a solicitação no momento, por favor, aguarde alguns instantes.';
    }

    public function success(): string {
        return 'Sucesso.';
    }

    public function invalidId(): string {
        return 'Id da loja inválido.';
    }

    public function invalidStoreEmail(): string {
        return 'Email da loja inválido.';
    }

    public function invalidPassword(): string {
        return 'Senha não atende aos requisitos necessários.';
    }

    public function notParamsDetected() {
        return 'Parâmetros não indentificados.';
    }

    public function loginSuccess() {
        return 'Login realizado com sucesso.';
    }

    public function accountNotFound() {
        return 'Usuário ou senha incorretos.';
    }

    public function inactiveAccount() {
        return 'Conta desativada. Entre em contato com nosso suporte para mais informações.';
    }

    public function tokenExpired() {
        return 'Token de acesso expirado. Realize o login novamente.';
    }

    public function invalidCNPJ() {
        return 'CNPJ inválido.';
    }

    public function storeAlreadyExists() {
        return 'Loja já cadastrada no sistema.';
    }

    public function storeInformationRegistered() {
        return 'Informações cadastradas com sucesso.';
    }

    public function storeInformationAlreadyRegistered() {
        return 'Informações já cadastradas no sistema.';
    }

    public function notBase64valid() {
        return 'Arquivo base64 inválido.';
    }

    public function imageRegistered() {
        return 'Imagem cadastrada com sucesso.';
    }

    public function noFolderCreated() {
        return 'Falha ao criar seu diretório. Tente novamente mais tarde ou contate nosso suporte.';
    }

    public function noImageCreated() {
        return 'Falha ao criar a imagem. Tente novamente mais tarde ou contate nosso suporte.';
    }

    public function logoAlreadyExists() {
        return 'Logo da loja já criado.';
    }
}