<?php

namespace BiBundle\Command;

use BiBundle\Entity\CardCategory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * #8051
 * Class BiCardsUpdateCommand
 * @package BiBundle\Command
 */
class BiCategoriesLoadCommand extends ContainerAwareCommand
{
    /**
     * Category name
     *
     * @var string
     */
    const COMMERCIAL_OPERATIONS_CATEGORY_NAME = 'Финансы / Коммерческие операции';

    protected function configure()
    {
        $this
            ->setName('bi:categories:load')
            ->setDescription('Load card categories')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force categories load');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('force')) {
            throw new \Exception('To force loading categories use the --force option.');
        }

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        foreach ($this->data as $categoryData) {
            if ($this->getContainer()->get('repository.card_category_repository')->findBy(['name' => $categoryData['name']])) {
                throw new \ErrorException("Category with name '{$categoryData['name']}' exists");
            }
            $cardCategory = new CardCategory();
            $entityManager->persist($cardCategory);
            $cardCategory->setName($categoryData['name']);
            $entityManager->flush($cardCategory);

            $cardCategory->setLocale('en');
            $cardCategory->setName($categoryData['name_en']);
            $entityManager->flush($cardCategory);
        }

        $output->writeln('Done.');
    }

    private $data = [
        [
            'name' => self::COMMERCIAL_OPERATIONS_CATEGORY_NAME,
            'name_en' => "Finance / Commercial operations"
        ],
        [
            'name' => "Реклама, маркетинг, сбыт",
            'name_en' => "Advertising, marketing, sales"
        ],
        [
            'name' => "Управление проектом",
            'name_en' => "Project management"
        ],
        [
            'name' => "Склад",
            'name_en' => "Warehouse"
        ],
        [
            'name' => "Управление персоналом",
            'name_en' => "Personnel Management"
        ],
        [
            'name' => "Стратегия и планирование",
            'name_en' => "Strategy and Planning"
        ]
    ];
}
