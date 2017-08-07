<?php
// src/Booking/UserBundle/Command/CreateUserCommand.php
namespace Booking\UserBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends Command
{
    protected function configure()
    {
        $this
        ->setName('booking:user:create')
        ->setDescription('Creates a new user if doesn\'t exist.')
        ->setHelp('Creates a new user if doesn\'t exist. Nothing else.')
        ->setDefinition([
            new InputArgument('username', InputArgument::REQUIRED, 'The username'),
            new InputArgument('email', InputArgument::REQUIRED, 'The email'),
            new InputArgument('password', InputArgument::REQUIRED, 'The password'),
            new InputOption('super-admin', null, InputOption::VALUE_NONE, 'Set the user as super admin'),
            new InputOption('inactive', null, InputOption::VALUE_NONE, 'Set the user as inactive'),
        ]);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $command = $this->getApplication()->find('fos:user:create');
        try {
            $returnCode = $command->run($input, $output);
        } catch (\Exception $e) {
            $username = $input->getArgument('username');
            $email = $input->getArgument('email');
            $output->writeln(sprintf('User with username <comment>%s</comment> or email <comment>%s</comment> <info>already exist</info>.', $username, $email));
        } finally {
            $output->writeln(sprintf('<info>You can now login</info>'));
        }
    }
}
