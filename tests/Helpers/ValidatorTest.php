<?php

use App\Helpers\Validator;
use PHPUnit\Framework\TestCase;

final class ValidatorTest extends TestCase {
    public function testValidateId() {
        $falseId = Validator::validateId(-100);
        $this->assertFalse($falseId, 'validating a not valid ID');

        $trueId = Validator::validateId(100);
        $this->assertTrue($trueId, 'validating a valid ID');
    }

    public function testValidateEmail() {
        $falseEmail = Validator::validateEmail('phpunit.com.br');
        $this->assertFalse($falseEmail, 'validating a false email address');

        $trueEmail = Validator::validateEmail('phpunit@email.com');
        $this->assertEquals($trueEmail, 'phpunit@email.com', 'validating a valid email address');
    }

    public function testValidatePassword() {
        $wrongPass = Validator::validatePassword('abc123');
        $this->assertEquals($wrongPass, 0, 'validating a not valid password 1');

        $wrongPass = Validator::validatePassword('PASS123');
        $this->assertEquals($wrongPass, 0, 'validating a not valid password 2');

        $wrongPass = Validator::validatePassword('PassPass');
        $this->assertEquals($wrongPass, 0, 'validating a not valid password 3');

        $okPass = Validator::validatePassword('PhpUnit1212');
        $this->assertEquals($okPass, 1, 'validating a valid password');
    }

    public function testValidateActiveAccount() {
        $store['store_status'] = Validator::FLAG_ACTIVE;
        $validator = Validator::validateActiveAccount($store);

        $this->assertTrue($validator);
    }

    public function testValidateinactiveAccount() {
        $store['store_status'] = Validator::FLAG_INACTIVE;
        $validator = Validator::validateActiveAccount($store);

        $this->assertFalse($validator);
    }

    public function testValidateCNPJ() {
        $cnpjs = $this->cnpjProvider();

        foreach ($cnpjs as $key => $test) {
            $validator = Validator::validateCNPJ($test[0]);
            $this->assertEquals($test[1], $validator, "test validate cnpj in position $key");
        }
    }

    public function testValidateName() {
        $name = '';
        $validador = Validator::validateName($name);
        $this->assertFalse($validador, 'testing a empty name');

        $name = 'ab';
        $validador = Validator::validateName($name);
        $this->assertFalse($validador, 'testing a short name');

        $name = 'It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout. The point of using Lorem Ipsum is that it has a more-or-less normal distribution of letters, as opposed to using "Content here, content here", making it look like readable English';
        $validador = Validator::validateName($name);
        $this->assertFalse($validador, 'testing a bigger name');

        $name = 'Php units';
        $validador = Validator::validateName($name);
        $this->assertTrue($validador, 'testing a correct name');
    }

    public function testValidateDatabaseStatus() {
        $status = 3;
        $validador = Validator::validateDatabaseStatus($status);
        $this->assertFalse($validador, 'testing a incorrect database status');

        $status = Validator::FLAG_ACTIVE;
        $validador = Validator::validateDatabaseStatus($status);
        $this->assertTrue($validador, 'testing a correct database status');

        $status = Validator::FLAG_INACTIVE;
        $validador = Validator::validateDatabaseStatus($status);
        $this->assertTrue($validador, 'testing a correct database status 2');
    }

    public function testeArrayKeysExists() {
        $array = ['a' => 1, 'b' => 1, 'c' => 1];
        $keys = ['a', 'b', 'c'];

        $validador = Validator::arrayKeysExists($keys, $array);
        $this->assertTrue($validador, 'testing request params required');
    }

    private function cnpjProvider() {
        return [
            ['12.345.678/0001-95', true],
            ['12.345.678/0001-96', false],
            ['11.222.333/0001-81', true],
            ['11.222.333/0001-82', false],
            ['12345678000195', true],
            ['12345678000196', false],
            ['123', false],
        ];
    }


}