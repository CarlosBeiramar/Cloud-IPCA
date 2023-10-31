<?php 
declare(strict_types=1);
namespace Tests;

use Models\User;
use PHPUnit\Framework\TestCase;

final class UserTest extends TestCase
{
    public function testUserFind(): void
    {
        $user = User::find();
        $this->assertNotEmpty($user);
    }
}