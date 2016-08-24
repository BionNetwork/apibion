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
use ApiBundle\Service\ApiKeyProvider;
use Symfony\Component\Console\Helper\Table;

/**
 * Show Api Keys
 */
class ShowKeyCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('api:keys:show')
            ->setDescription('Show available api keys')
            ->addArgument('key', InputArgument::OPTIONAL, 'key to show');
    }

    /**
     * Show api keys
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ClientInterface $client */
        $client = $this->getContainer()->get('snc_redis.session');
        $key = $input->getArgument('key');

        if (!empty($key)) {
            $result = $client->hget(ApiKeyProvider::API_KEYS_NAME, $key);
            $result = [$result];
        } else {
            $result = $client->hgetall(ApiKeyProvider::API_KEYS_NAME);
        }

        $table = new Table($output);
        $headers = ['id', 'description', 'valid_until'];

        foreach ($result as $k => $item) {
            $itemValues = array_merge(['id' => $k], json_decode($item, true));
            $itemKeys = array_keys($itemValues);
            $itemKeys = array_diff($itemKeys, $headers);
            if (!empty($itemKeys)) {
                foreach ($itemKeys as $singleKey) {
                    $headers[] = $singleKey;
                }
            }

            $table->addRow($itemValues);
        }
        $table->setHeaders($headers);

        $table->render();
    }
}