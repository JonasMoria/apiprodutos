<?php

namespace App\Lang;

class LangES implements LangInterface {
    public function serverError(): string {
        return 'La solicitud no se pudo cumplir en este momento, espere unos momentos.';
    }

    public function success(): string {
        return 'Éxito.';
    }

    public function invalidId(): string {
        return 'ID de tienda no válido.';
    }

    public function invalidStoreEmail(): string {
        return 'Correo electrónico de la tienda no válido.';
    }

    public function invalidPassword(): string {
        return 'La contraseña no cumple con los requisitos necesarios.';
    }

    public function notParamsDetected() {
        return 'Parámetros no identificados.';
    }

    public function loginSuccess() {
        return 'Inicio de sesión correcto.';
    }

    public function accountNotFound() {
        return 'Usuario o contraseña incorrectos.';
    }

    public function inactiveAccount() {
        return 'Cuenta desactivada. Póngase en contacto con nuestro soporte para más información.';
    }

    public function tokenExpired() {
        return 'Token de acceso caducado. ingresar de nuevo.';
    }
}