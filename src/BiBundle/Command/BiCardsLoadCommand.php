<?php

namespace BiBundle\Command;

use BiBundle\Entity\Argument;
use BiBundle\Entity\Card;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BiCardsLoadCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bi:cards:load')
            ->setDescription('Load cards')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force cards load');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        throw new \ErrorException('Not finished');

        if (!$input->getOption('force')) {
            throw new \Exception('To force loading cards use the --force option.');
        }

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        foreach ($this->data as $cardData) {
            if ($entityManager->getRepository(Card::class)->findBy(['name' => $cardData['name']])) {
                throw new \ErrorException("Card with name '{$cardData['name']}' exists");
            }
            $card = new Card();
            $entityManager->persist($card);
            $card->setName($cardData['name']);
            $card->setDescription($cardData['description']);
            $card->setRating(rand(4, 20) * 5);
            $card->setPrice(rand(4, 20) * 10);
            $card->setDescriptionLong($cardData['description_long']);
            $card->setAuthor($cardData['author']);
            $entityManager->flush($card);
            $card->setLocale('en');
            $card->setName($cardData['name_en']);
            $card->setDescription($cardData['description_en']);
            $card->setDescriptionLong($cardData['description_long_en']);
            $card->setAuthor($cardData['author_en']);
            $entityManager->flush($card);
            foreach ($cardData['arguments'] as $argumentData) {
                $argument = new Argument();
                $entityManager->persist($argument);
                $card->addArgument($argument);
                $argument->setCard($card);
                $argument->setCode($argumentData['code']);
                $argument->setName($argumentData['name']);
                $argument->setDescription($argumentData['description']);
                $entityManager->flush($argument);
                $argument->setLocale('en');
                $argument->setName($argumentData['name_en']);
                $argument->setDescription($argumentData['description_en']);
                $entityManager->flush($argument);
            }
        }
        $output->writeln('Done.');
    }

    private $data = [
        [
            'name' => "Коэффициент рентабельности продаж (ROS)",
            'name_en' => "Return on sales (ROS)",
            'description' => "Оценка операционной деятельности хозяйствующего субъекта",
            'description_en' => "The evaluation an entity's operating performance",
            'description_long' => "Рентабельность продаж, которую часто называют операционной прибылью, является финансовым показателем, который показывает, насколько эффективно компания получает прибыль от своих доходов. Другими словами, он измеряет производительность компании, анализируя, какой процент от общей выручки компании фактически преобразуются в прибыль компании.",
            'description_long_en' => "Return on sales, often called the operating profit margin, is a financial ratio that calculates how efficiently a company is at generating profits from its revenue. In other words, it measures a company’s performance by analyzing what percentage of total company revenues are actually converted into company profits. Since the return on sales equation measures the percentage of sales that are converted to income, it shows how well the company is producing its core products or services and how well the management teams is running it.",
            'author' => "Зорин В.",
            'author_en' => "Zorin V.",
            'arguments' => [
                [
                    'code' => 'NI',
                    'name' => 'Прибыль',
                    'name_en' => 'Net Income',
                    'description' => 'Величина прибыли до уплаты процентов и налогообложения',
                    'description_en' => 'Net Income before interest and tax',
                ],
                [
                    'code' => 'S',
                    'name' => 'Объем продаж',
                    'name_en' => 'Sales',
                    'description' => 'Выручка от реализации',
                    'description_en' => 'Revenues from sales',
                ]
            ]
        ]
    ];

}
