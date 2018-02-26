<?php
declare(strict_types=1);

namespace Tests\Pwned;

use App\Pwned\HashedPassword;
use PHPUnit\Framework\TestCase;

class HashedPasswordTest extends TestCase
{
    /**
     * @test
     */
    public function get_prefix_returns_first_5_char_uppercase()
    {
        $hashedPassword = HashedPassword::hash('password');

        self::assertEquals('5BAA6', $hashedPassword->getPrefix());
    }

    /**
     * @test
     */
    public function get_suffix_returns_the_rest_of_the_chars_uppercase()
    {
        $hashedPassword = HashedPassword::hash('password');

        self::assertEquals('1E4C9B93F3F0682250B6CF8331B7EE68FD8', $hashedPassword->getSuffix());
    }
}