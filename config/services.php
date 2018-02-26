<?php
declare(strict_types=1);

use Symfony\Component\DependencyInjection\ContainerBuilder;

$container = new ContainerBuilder();

$container->autowire(\GuzzleHttp\Client::class)->setPublic(false);
$container->autowire(\App\Pwned\PwnedPasswords::class)->setPublic(false);

/*
 * Commands
 */
$container
    ->autowire(\App\Command\PwnedCommand::class)
    ->setPublic(true)
    ->addTag('console.command');


$container->compile();

return $container;
