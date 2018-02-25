<?php
declare(strict_types=1);

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

$container = new ContainerBuilder();

$container
    ->register(\GuzzleHttp\Client::class, \GuzzleHttp\Client::class);

$container
    ->register(\App\Pwned\PwnedPasswords::class, \App\Pwned\PwnedPasswords::class)
    ->addArgument(new Reference(\GuzzleHttp\Client::class));


/*
 * Commands
 */
$container
    ->register(\App\Command\PwnedCommand::class, \App\Command\PwnedCommand::class)
    ->addArgument(new Reference(\App\Pwned\PwnedPasswords::class))
    ->addTag('console.command');


return $container;
