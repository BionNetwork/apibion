<?php
/**
 * Created by PhpStorm.
 * User: imnareznoi
 * Date: 06.07.16
 * Time: 14:11
 */

namespace BiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class OverdueCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('issues:overdue:update')
            ->setDescription('Updating overdue attribute of issues');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $this->getContainer()->get('statistics.service');
        $output->writeln("<info>Overdues updated</info>");
    }
}
