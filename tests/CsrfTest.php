<?php
namespace Tests;

use PHPUnit\Framework\TestCase;
use App\Core\Csrf;

class CsrfTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        $_POST    = [];
    }

    public function testToken(): void
    {
        $token = Csrf::generate();
        $this->assertNotEmpty($token);
        $this->assertEquals(64, strlen($token));
        $this->assertMatchesRegularExpression('/^[0-9a-f]+$/', $token);
    }

    public function testSameTokenIfCalledTwice(): void
    {
        $this->assertEquals(Csrf::generate(), Csrf::generate());
    }

    public function testNoPOSTToken(): void
    {
        Csrf::generate();
        $this->assertFalse(Csrf::verify());
    }

    public function testValidToken(): void
    {
        $token = Csrf::generate();
        $_POST['csrf_token'] = $token;
        $this->assertTrue(Csrf::verify());
    }

    public function testWrongToken(): void
    {
        Csrf::generate();
        $_POST['csrf_token'] = 'xgnkdghdgh';
        $this->assertFalse(Csrf::verify());
    }
}