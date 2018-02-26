<?php
declare(strict_types=1);

namespace App\Command;

use App\Pwned\PwnedPasswords;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class PwnedCommand extends Command
{
    private $pwnedPasswords;

    public function __construct(PwnedPasswords $pwnedPasswords)
    {
        parent::__construct();

        $this->pwnedPasswords = $pwnedPasswords;
    }

    protected function configure()
    {
        $this->setName('pwned')
            ->setDescription('Check a password against the Pwned Passwords database');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        $question = new Question('What password to check? ');
        $question->setHidden(true);
        $password = $helper->ask($input, $output, $question);

        $occurrences = $this->pwnedPasswords->check($password);

        if ($occurrences === 0) {
            $output->writeln('<info>As far as I know, this has not appeared in any breach</info>');
        } else {
            $output->writeln("<error>Oh no! This password has occurred $occurrences times in breaches</error>");
        }
    }
}
