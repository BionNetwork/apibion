<?php

namespace BiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BiCardsPurgeCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bi:cards:purge')
            ->setDescription('Remove all cards and their related items: activations, purchases, etc')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force categories load');;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('force')) {
            throw new \Exception('To force purging cards use the --force option.');
        }

        $this->getContainer()->get('doctrine.orm.entity_manager')->getConnection()
            ->exec('TRUNCATE card CASCADE');

        $output->writeln('Done.');
    }

}
