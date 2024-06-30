<?php

namespace App\Lang;

class LangES implements LangInterface {
    public function serverError() {
        return 'La solicitud no se pudo cumplir en este momento, espere unos momentos.';
    }

    public function success() {
        return 'Éxito';
    }
}