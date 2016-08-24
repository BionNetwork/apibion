<?php
/**
 * @package    BiBundle
 * @author     nareznoi
 * @version    $Id: $
 */

namespace BiBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class DoctrineEntityListenerPass
 */
class DoctrineEntityListenerPass implements CompilerPassInterface
{

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definition = $container->getDefinition('bi.doctrine.entity_listener_resolver');
        $services = $container->findTaggedServiceIds('doctrine.orm.entity_listener');

        foreach ($services as $service => $attributes) {
            $definition->addMethodCall(
                'addMapping',
                array($container->getDefinition($service)->getClass(), $service)
            );
        }
    }
}