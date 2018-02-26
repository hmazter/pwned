<?php
declare(strict_types=1);

namespace Tests\Pwned;

use App\Pwned\PwnedPasswords;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class PwnedPasswordsTest extends TestCase
{
    /**
     * @test
     */
    public function check_returns_0_for_not_found_password()
    {
        $client = $this->getMockClient(200, "1E4C9B93F3F0682250B6CF8331B7EE68FD8:100\n1E4C9B93F3F0682250B6CF8331B7EE68FDD:50");

        $pwnedPasswords = new PwnedPasswords($client);

        self::assertEquals(0, $pwnedPasswords->check('pass'));
    }

    /**
     * @test
     */
    public function check_returns_the_breached_count_for_found_password()
    {
        $client = $this->getMockClient(200, "1E4C9B93F3F0682250B6CF8331B7EE68FD8:100\n1E4C9B93F3F0682250B6CF8331B7EE68FDD:50");

        $pwnedPasswords = new PwnedPasswords($client);

        self::assertEquals(100, $pwnedPasswords->check('password'));
    }

    private function getMockClient(int $statusCode, string $body)
    {
        // Create a mock and queue two responses.
        $mock = new MockHandler([
            new Response($statusCode, [], $body),
        ]);

        $handler = HandlerStack::create($mock);
        return new Client(['handler' => $handler]);
    }
}
