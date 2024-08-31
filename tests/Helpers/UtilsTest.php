<?php

use App\Helpers\Utils;
use PHPUnit\Framework\TestCase;

final class UtilsTest extends TestCase {
    public function testConvertStringToSha512() {
        $string = 'phpunit';
        $stringCoded = Utils::convertToSha512($string);
        $stringExpected = '5cd9c18fdc57bcaeec769ddf08dd212e8944bed1cf390ce951146666bbdf0791294c6fb3f7ef7519fc8f13e32263236470c224ccac0ef0673671b251774f229d';

        $this->assertEquals($stringExpected, $stringCoded, 'sha512 conversion test');
    }

    public function testRemoveDoubleSpaces() {
        $string = 'php        unit';
        $stringExpected = 'php unit';
        $stringSanitized = Utils::removeDoubleSpaces($string);

        $this->assertEquals($stringExpected, $stringSanitized, 'removing multi white spaces');
    }

    public function testFilterNumbersOnly() {
        $string = '127.0.0.1::8080';
        $stringExpected = '1270018080';
        $stringSanitized = Utils::filterNumbersOnly($string);

        $this->assertEquals($stringExpected, $stringSanitized, 'removing not number characters');
    }

    public function testConvertStringToDouble() {
        $string = 'abc';
        $double = Utils::convertToFloat($string);
        $doubleExpected = 0;

        $this->assertEquals($doubleExpected, $double, 'converting a not double string');

        $string = '100.00';
        $double = Utils::convertToFloat($string);
        $doubleExpected = 100.00;

        $this->assertEquals($doubleExpected, $double, 'converting string to double number');
    }
}