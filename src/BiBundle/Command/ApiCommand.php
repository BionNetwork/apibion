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

class ApiCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('api:test')
            ->setDescription('Testing service call for BI Platform API');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $service = $this->getContainer()->get('bi.backend.service');
        $result = $service->putResource($resource);
        dump($result);
        $output->writeln("<info>Remove API call sent.</info>");
    }
}
