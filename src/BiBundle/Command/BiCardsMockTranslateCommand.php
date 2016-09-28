<?php

namespace BiBundle\Command;

use Behat\Transliterator\Transliterator;
use BiBundle\Entity\Argument;
use BiBundle\Entity\Card;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class BiCardsMockTranslateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('bi:cards:mock-translate')
            ->setDescription('')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Force mock translation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$input->getOption('force')) {
            throw new \Exception('To force mock translating cards use the --force option.');
        }
        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');
        $cards = $entityManager->getRepository(Card::class)->findAll();
        foreach ($cards as $card) {
            $card->setName($this->translate($card->getName()));
            $card->setDescription($this->translate($card->getDescription()));
            $card->setDescriptionLong($this->translate($card->getDescriptionLong()));
            $card->setAuthor($this->translate($card->getAuthor()));
            $card->setLocale('en');
            foreach ($card->getArgument() as $argument) {
                /** @var Argument $argument */
                $argument->setName($this->translate($argument->getName()));
                $argument->setLocale('en');
            }
        }
        $entityManager->flush();

        $output->writeln('Done.');
    }

    private function translate($text)
    {
        return Transliterator::transliterate($text, ' ');
    }

}
