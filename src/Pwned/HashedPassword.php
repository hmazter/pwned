<?php
declare(strict_types=1);

namespace App\Pwned;

class HashedPassword
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @param string $hash
     */
    private function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    public static function hash(string $password): HashedPassword
    {
        return new self(strtoupper(sha1($password)));
    }

    public function getPrefix(): string
    {
        return substr($this->hash, 0, 5);
    }

    public function getSuffix(): string
    {
        return substr($this->hash, 5);
    }
}
