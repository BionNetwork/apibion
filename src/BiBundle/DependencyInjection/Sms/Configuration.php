<?php

namespace BiBundle\DependencyInjection\Sms;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * Generates the configuration tree.
     *
     * @return TreeBuilder
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('sms_service');

        $rootNode
            ->addDefaultsIfNotSet()
            ->children()
                ->arrayNode('class')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('factory')->defaultValue('BiBundle\Service\Sms\Gateway\Factory')->end()
                        ->scalarNode('client')->defaultValue('BiBundle\Service\Sms\Client')->end()
                    ->end()
                ->end()
            ->end()
            ->children()
                ->scalarNode('enabled')->defaultValue(false)->end()
                ->variableNode('channels')->end()
                ->variableNode('clients')->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
