<?php

declare(strict_types=1);

namespace Tests;

use Libraries\Encrypt;
use PHPUnit\Framework\TestCase;

final class EncryptTest extends TestCase
{
    public function testEncode(): void
    {
        $string = "PASSWORDTEST";
        $encrypted = Encrypt::encode($string);
        $this->assertEquals("UFdOV29TZWtLdFdvMlZXd3p2WFRFQT09", $encrypted);
    }

    public function testEncodeJWT(): void
    {
        $encrypted = Encrypt::encryptJwt("EXAMPLE");
        $this->assertArrayHasKey('token', $encrypted);
    }


    public function testDecryptJWT(): void
    {
        $encrypted = Encrypt::encryptJwt("EXAMPLE");
        if (!isset($encrypted['token'])) {
            $this->fail('This test intentionally fails in encrypted.');
        }
        $descrypted = Encrypt::decryptJwt($encrypted['token']);
        if (!isset($descrypted['sub'])) {
            $this->fail('This test intentionally fails in decrypted.');
        }
        $this->assertEquals("EXAMPLE", $descrypted['sub']);
    }
}
