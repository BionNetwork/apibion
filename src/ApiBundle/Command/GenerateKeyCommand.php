<?php
/**
 * @package    ApiBundle\Command
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Command;

use Predis\ClientInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Ramsey\Uuid\Uuid;
use ApiBundle\Service\ApiKeyProvider;

/**
 * Generate api keys command
 */
class GenerateKeyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('api:keys:generate')
            ->setDescription('Generates and saves api key')
            ->addArgument(
                'description',
                InputArgument::REQUIRED,
                'Client that will use key'
            )
            ->addArgument('valid', InputArgument::OPTIONAL, 'Valid until');
    }

    /**
     * Generate api keys
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $text = $input->getArgument('description');
        $validUntil = $input->getArgument('valid');
        $valid = null;
        if (!empty($validUntil)) {
            $valid = strtotime($validUntil);
        }

        /** @var ClientInterface $client */
        $client = $this->getContainer()->get('snc_redis.session');

        $key = Uuid::uuid4();

        $client->hset(ApiKeyProvider::API_KEYS_NAME, $key, json_encode(['description' => $text, 'valid_until' => $valid]));

        $output->writeln(sprintf("<info>Generated new api key. Id: %s</info>", $key));
    }
}