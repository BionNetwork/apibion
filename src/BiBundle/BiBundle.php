<?php

namespace BiBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use BiBundle\DependencyInjection\Compiler\DoctrineEntityListenerPass;

class BiBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DoctrineEntityListenerPass());
    }

    public function getContainerExtension()
    {
        //return new SmsServiceExtension();
    }
}
