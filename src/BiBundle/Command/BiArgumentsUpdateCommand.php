<?php

namespace BiBundle\Command;

use BiBundle\Entity\Argument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * #8051
 * Class BiCardsUpdateCommand
 * @package BiBundle\Command
 */
class BiArgumentsUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bi:arguments:update')
            ->setDescription('Update arguments')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force arguments update');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('force')) {
            throw new \Exception('To force updating arguments use the --force option.');
        }

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var Argument[] $arguments */
        $arguments = $this->getContainer()->get('repository.argument_repository')->findAll();

        foreach ($arguments as $argument) {
            $argument->setName($this->data[$argument->getId()]['name']);
            $entityManager->flush($argument);

            $argument->setLocale('en');
            $argument->setName($this->data[$argument->getId()]['name_en']);
            $entityManager->flush($argument);
        }
        $output->writeln('Done.');
    }

    private $data = [
        1 => [
            'name' => "Организация",
            'name_en' => "Organization",
        ],
        2 => [
            'name' => "Выручка",
            'name_en' => "Revenue",
        ],
        3 => [
            'name' => "Выручка без НДС",
            'name_en' => "Revenue without VAT",
        ],
        4 => [
            'name' => "Услуги/Товар",
            'name_en' => "Services/Goods",
        ],
        5 => [
            'name' => "Контрагент",
            'name_en' => "Partner",
        ],
        6 => [
            'name' => "Договор",
            'name_en' => "Contract",
        ],
        7 => [
            'name' => "Проект",
            'name_en' => "Project",
        ],
        8 => [
            'name' => "Дата",
            'name_en' => "Date",
        ],
        9 => [
            'name' => "Организация",
            'name_en' => "Organization",
        ],
        10 => [
            'name' => "Сумма задолженности",
            'name_en' => "Amount of debt",
        ],
        11 => [
            'name' => "Контрагент",
            'name_en' => "Partner",
        ],
        12 => [
            'name' => "Дата",
            'name_en' => "Date",
        ],
        13 => [
            'name' => "Организация",
            'name_en' => "Organization",
        ],
        14 => [
            'name' => "Сумма задолженности",
            'name_en' => "Amount of debt",
        ],
        15 => [
            'name' => "Контрагент",
            'name_en' => "Partner",
        ],
        16 => [
            'name' => "Дата",
            'name_en' => "Date",
        ],
    ];
}
