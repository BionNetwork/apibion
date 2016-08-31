<?php

namespace BiBundle\DependencyInjection\Sms;

use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;

/**
 * Extension used to load the configuration, set parameters, and initialize the channels
 *
 */
class SmsServiceExtension extends Extension
{
    /**
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('sms_service.config', $config);

        foreach ($config['class'] as $name => $class) {
            $container->setParameter(sprintf('sms_service.%s.class', $name), $class);
        }

        foreach ($config['channels'] as $name => $channel) {
            $this->loadChannel($name, $channel, $container);
        }

        foreach ($config['clients'] as $name => $client) {
            $this->loadClient($name, $client, $container);
        }
    }

    /**
     * Load Channel configuration
     *
     * @param $name
     * @param $channel
     * @param ContainerBuilder $container
     */
    protected function loadChannel($name, $channel, ContainerBuilder $container)
    {
        $factoryClass = $container->getParameter('sms_service.factory.class');
        $channelDef = new Definition($channel['class']);
        $channelDef->addTag('sms_service.channel', array('alias' => $name));
        $channelDef->setFactory(array($factoryClass, 'factory'));
        $channelDef->addArgument($name);

        $channelDef->addMethodCall('setConfig', [$channel]);
        $channelDef->addMethodCall('setFromArray', [$channel]);

        $channelId = sprintf('sms_service.%s_channel', $name);
        $container->setDefinition($channelId, $channelDef);
    }

    /**
     * Load client configuration
     *
     * @param string $name client's name
     * @param array $client client's config
     * @param ContainerBuilder $container
     */
    protected function loadClient($name, $client, ContainerBuilder $container)
    {
        $config = $container->getParameter('sms_service.config');
        $clientDef = new Definition($container->getParameter('sms_service.client.class'));
        $channelId = new Reference(sprintf('sms_service.%s_channel', $client['alias']));
        $clientDef->addArgument($channelId);
        $clientDef->addMethodCall('setEnabled', [$config['enabled']]);

            $clientId = sprintf('sms_service.%s', $name);
        $container->setDefinition($clientId, $clientDef);
        $container->setAlias(sprintf('sms_service.%s_client', $name), $clientId);
    }
}
