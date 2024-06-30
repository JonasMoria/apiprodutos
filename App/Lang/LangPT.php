<?php

namespace App\Lang;

use App\Lang\LangInterface;

class LangPT implements LangInterface {
    public function serverError() {
        return 'Não foi possível atender a solicitação no momento, por favor, aguarde alguns instantes.';
    }

    public function success() {
        return 'Sucesso';
    }
}