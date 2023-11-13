<?php

declare(strict_types=1);

namespace Tests;

use Config\Database;
use Exception;
use PHPUnit\Framework\TestCase;

final class DatabaseTest extends TestCase
{

    protected function setUp(): void
    {
        if (!extension_loaded('pdo')) {
            $this->markTestSkipped(
                'The Mysql extension is not available',
            );
        }
    }

    public function testGetConnection(): void
    {
        try {
            $pdoInstance = Database::getConnection();
            $this->assertInstanceOf(\PDO::class, $pdoInstance);
        } catch (Exception $e) {
            $this->fail('Error to get connection to database.');
        }
    }


    public function testGetResults(): void
    {
        try {
            $arrayInstance = Database::getResults("SELECT * FROM user");
            $this->assertIsArray($arrayInstance);
        } catch (Exception $e) {
            $this->fail('Error to get results to database.');
        }
    }

    public function testGetOperation(): void
    {
        try {
            $data = Database::operation("DELETE FROM  `uc` WHERE iduc=99");
            $this->assertTrue($data);
        } catch (Exception $e) {
            $this->fail('Error to send operation to database.');
        }
    }
}
