<?php

use App\Lang\Lang;
use App\Lang\LangEN;
use App\Lang\LangES;
use App\Lang\LangPT;
use PHPUnit\Framework\TestCase;

final class LangTest extends TestCase {
    public function testGetLangDefault() {
        $langInstance = Lang::getLang();

        $this->assertInstanceOf(langEN::class, $langInstance, 'testing default system language');
    }

    public function testGetLangPT() {
        $this->setServerLang('pt');
        $langInstance = Lang::getLang();

        $this->assertInstanceOf(LangPT::class, $langInstance, 'test setting Portuguese system language');
    }

    public function testGetLangEN() {
        $this->setServerLang('en');
        $langInstance = Lang::getLang();

        $this->assertInstanceOf(LangEN::class, $langInstance, 'test setting English system language');
    }

    public function testGetLangES() {
        $this->setServerLang('es');
        $langInstance = Lang::getLang();

        $this->assertInstanceOf(LangES::class, $langInstance, 'test setting Spanish system language');
    }

    public function testLanguageEnglishMessage() {
        $this->setServerLang('en');
        $lang = Lang::getLang();  

        $msg = $lang->invalidId();
        $this->assertEquals($msg, 'Invalid store id.', 'testing english return message');
    }

    public function testLanguagePortugueseMessage() {
        $this->setServerLang('pt');
        $lang = Lang::getLang();  

        $msg = $lang->invalidId();
        $this->assertEquals($msg, 'Id da loja inválido.', 'testing portuguese return message');
    }

    public function testLanguageSpanishMessage() {
        $this->setServerLang('es');
        $lang = Lang::getLang();  

        $msg = $lang->invalidId();
        $this->assertEquals($msg, 'ID de tienda no válido.', 'testing spanish return message');
    }

    private function setServerLang(string $lang) {
        isset($_SERVER);
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = $lang;
    }
}