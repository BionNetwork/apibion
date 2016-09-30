<?php

namespace BiBundle\Command;

use BiBundle\Entity\Card;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * #8051
 * Class BiCardsUpdateCommand
 * @package BiBundle\Command
 */
class BiCardsUpdateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bi:cards:update')
            ->setDescription('Update cards')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force cards load');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('force')) {
            throw new \Exception('To force updating cards use the --force option.');
        }

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        /** @var Card[] $cards */
        $cards = $this->getContainer()->get('repository.card_repository')->findBy(['id' => [21, 22, 23]]);
        if (count($cards) !== 3) {
            throw new \ErrorException();
        }

        foreach ($cards as $card) {
            $card->setName($this->data[$card->getId()]['name']);
            $card->setDescription($this->data[$card->getId()]['description']);
            $card->setDescriptionLong($this->data[$card->getId()]['description_long']);
            $card->setAuthor($this->data[$card->getId()]['author']);
            $card->setRating($this->data[$card->getId()]['rating']);
            $card->setPrice($this->data[$card->getId()]['price']);
            $entityManager->flush($card);

            $card->setLocale('en');
            $card->setName($this->data[$card->getId()]['name_en']);
            $card->setDescription($this->data[$card->getId()]['description_en']);
            $card->setDescriptionLong($this->data[$card->getId()]['description_long_en']);
            $card->setAuthor($this->data[$card->getId()]['author_en']);
            $card->setPrice($this->data[$card->getId()]['price_en']);
            $entityManager->flush($card);
        }
        $output->writeln('Done.');
    }

    private $data = [
        21 => [
            'name' => "Выручка",
            'name_en' => "Revenue",
            'description' => "Выручка от продажи готовой продукции, товаров",
            'description_en' => "Revenue from the sale of finished-products, goods",
            'description_long' => "Доход, получаемый от продажи товаров или услуг, или любое другое капитала или активов, полученных предприятием в результате основной деятельности до вычета расходов. Прибыль обычно показывает верхнюю границу (доход или убыток), из которой получают чистую прибыль путем вычетания всех расходв, издержек.",
            'description_long_en' => "The income generated from sale of goods or services, or any other use of capital or assets, associated with the main operations of an organization before any costs or expenses are deducted. Revenue is shown usually as the top item in an income (profit and loss) statement from which all charges, costs, and expenses are subtracted to arrive at net income.",
            'author' => "Эттон",
            'author_en' => "Etton",
            'price' => 80,
            'price_en' => 1.15,
            'rating' => 5
        ],
        22 => [
            'name' => "Дебеторская задолженность",
            'name_en' => "Receivables",
            'description' => "Сумма задолженности перед компанией",
            'description_en' => "The amount of debt owed to the company",
            'description_long' => "Дебиторская задолженность относится к оплаченным счетам компании или это деньги компании причитающихся от своих клиентов. Дебеторскя задолженность предсталяет собой кредит, представленный компанией и который должен быть оплачен за короткий период времени, начиная от нескольких ней до года.",
            'description_long_en' => "Accounts receivable refers to the outstanding invoices a company has or the money the company is owed from its clients. Receivables essentially represent a line of credit extended by a company and due within a relatively short time period, ranging from a few days to a year.",
            'author' => "Эттон",
            'author_en' => "Etton",
            'price' => 75,
            'price_en' => 1.20,
            'rating' => 5
        ],
        23 => [
            'name' => "Кредиторская задолженность",
            'name_en' => "Accounts payable",
            'description' => "Платежи, причитающиеся от компании к поставщикам и другим кредиторам",
            'description_en' => "Payments owed by the company to suppliers and other creditors",
            'description_long' => "Кредиторская задолженность это сумма всех краткосрочных обязательств за предоставленые услуги и продукцию. В случае если кредиторская задолженность не выплачивается, то кредиторская задолженность считается не оплаченной, что может вызвать штраф или выплату процентов.",
            'description_long_en' => "Accounts payable is the aggregate amount of an entity's short-term obligations to pay suppliers for products and services which the entity purchased on credit. If accounts payable are not paid within the payment terms agreed to with the supplier, the payables are considered to be in default, which may trigger a penalty or interest payment, or the revocation or curtailment of additional credit from the supplier.",
            'author' => "Эттон",
            'author_en' => "Etton",
            'price' => 100,
            'price_en' => 1.50,
            'rating' => 5
        ]
    ];
}
