<?php
declare(strict_types=1);

namespace App\Pwned;

use GuzzleHttp\Client;

class PwnedPasswords
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function check($password): int
    {
        $hash = HashedPassword::hash($password);
        $suffixes = $this->queryApi($hash->getPrefix());

        return $suffixes[$hash->getSuffix()] ?? 0;
    }

    private function queryApi(string $prefix): array
    {
        $response = $this->client->get('https://api.pwnedpasswords.com/range/' . $prefix);
        $content = $response->getBody()->getContents();

        $suffixes = [];
        $rows = explode("\n", $content);
        foreach ($rows as $row) {
            list($suffix, $count) = explode(':', trim($row));
            $suffixes[$suffix] = (int)$count;
        }

        return $suffixes;
    }
}
