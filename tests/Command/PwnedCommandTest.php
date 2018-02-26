<?php
declare(strict_types=1);

namespace Tests\Command;

use App\Command\PwnedCommand;
use App\Pwned\PwnedPasswords;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class PwnedCommandTest extends TestCase
{
    /**
     * @test
     * @throws \ReflectionException
     */
    public function command_gives_positive_feedback_for_non_breached_password()
    {
        $pwnedPasswordsMock = $this->createMock(PwnedPasswords::class);
        $pwnedPasswordsMock->expects(self::once())->method('check')->with('password')->willReturn(0);

        $application = new Application();
        $application->add(new PwnedCommand($pwnedPasswordsMock));

        $command = $application->find('pwned');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['password']);
        $commandTester->execute(['command' => $command->getName()]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Good!', $output);
    }

    /**
     * @test
     * @throws \ReflectionException
     */
    public function command_gives_negative_feedback_for_breached_password()
    {
        $pwnedPasswordsMock = $this->createMock(PwnedPasswords::class);
        $pwnedPasswordsMock->expects(self::once())->method('check')->with('password')->willReturn(10);

        $application = new Application();
        $application->add(new PwnedCommand($pwnedPasswordsMock));

        $command = $application->find('pwned');
        $commandTester = new CommandTester($command);
        $commandTester->setInputs(['password']);
        $commandTester->execute(['command' => $command->getName()]);

        $output = $commandTester->getDisplay();
        $this->assertContains('Oh no!', $output);
    }
}