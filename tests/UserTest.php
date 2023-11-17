<?php

declare(strict_types=1);

namespace Tests;

use Models\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    protected function setUp(): void
    {
        if (!extension_loaded('pdo')) {
            $this->markTestSkipped(
                'The Mysql extension is not available',
            );
        }
    }

    public function testUserInsert(): void
    {
        $user_class = new User();
        $user_class->name = "Test Unit";
        $user_class->email = "testunit@example.pt";
        $user_class->type = 1;
        $user_class->apikey = md5(openssl_random_pseudo_bytes(17) . date('Y-m-d H:i:s'));
        $user_class->password = "UFdOV29TZWtLdFdvMlZXd3p2WFRFQT09";
        $res = $user_class->insert();
        $this->assertNotFalse($res, 'The id should not be false.');
    }

    public function testUserFind(): void
    {
        $user = User::find();
        $this->assertNotEmpty($user);
    }


    public function testUserUpdate(): void
    {
        $user = User::find("*", ["email" => "testunit@example.pt"]);
        $user_class = new User();
        $user_class->name = "Test Unit update";
        $user_class->email = "testunit@example.pt";
        $user_class->type = 1;
        $user_class->apikey = md5(openssl_random_pseudo_bytes(17) . date('Y-m-d H:i:s'));
        $user_class->password = "UFdOV29TZWtLdFdvMlZXd3p2WFRFQT09";
        $user_class->iduser = $user[0]->iduser;
        $res = $user_class->update();
        $this->assertNotFalse($res, 'Updated Error.');
    }

    public function testUserDelete(): void
    {
        $user = User::find("*", ["email" => "testunit@example.pt"]);
        $user_class = new User();
        $user_class->iduser = $user[0]->iduser;
        $res = $user_class->delete();
        $this->assertNotFalse($res, 'Delete Error.');
    }
}
