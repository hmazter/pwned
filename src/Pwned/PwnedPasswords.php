<?php
declare(strict_types=1);

namespace App\Pwned;

use GuzzleHttp\Client;

class PwnedPasswords
{
    public function check($password): int
    {
        $hash = HashedPassword::hash($password);
        $suffixes = $this->queryApi($hash->getPrefix());

        return $suffixes[$hash->getSuffix()] ?? 0;
    }

    private function queryApi(string $prefix): array
    {
        $uri = 'https://api.pwnedpasswords.com/range/' . $prefix;
        $client = new Client();
        $response = $client->get($uri);
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
