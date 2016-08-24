<?php
/**
 * @package    ApiBundle\Command
 * @author     miholeus <me@miholeus.com> {@link http://miholeus.com}
 * @version    $Id: $
 */

namespace ApiBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Command that shows tokens on project
 */
class TokenCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('api:tokens:show')
            ->setDescription('Show available api tokens')
            ->addArgument('token', InputArgument::OPTIONAL, 'token to show');
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
        $provider = $this->getContainer()->get('api.key_provider');
        if ($input->getArgument('token')) {
            $token = $input->getArgument('token');
            $user = $provider->getUserByToken($token);
            $table = new Table($output);
            $headers = ['user_id', 'login', 'email', 'phone'];
            $table->setHeaders($headers);
            if ($user) {
                $table->addRow([
                    'user_id' => $user->getId(),
                    'login' => $user->getLogin(),
                    'email' => $user->getEmail(),
                    'phone' => $user->getPhone()
                ]);
            }

            $table->render();

        } else {
            $tokens = $provider->getTokens();
            foreach ($tokens as $token) {
                $output->writeln($token);
            }
        }
    }
}