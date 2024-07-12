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
}