<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new BiBundle\BiBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Snc\RedisBundle\SncRedisBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Doctrine\Bundle\MongoDBBundle\DoctrineMongoDBBundle(),
            new Gregwar\CaptchaBundle\GregwarCaptchaBundle(),
            new Craue\FormFlowBundle\CraueFormFlowBundle(),
            new Tetranz\Select2EntityBundle\TetranzSelect2EntityBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new FOS\RestBundle\FOSRestBundle(),
            new Nelmio\ApiDocBundle\NelmioApiDocBundle(),
            new ApiBundle\ApiBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'), true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
    /**
     * Custom cache dir if dev/test environment to improve vagrant performance.
     *
     * @return string
     */
    public function getCacheDir()
    {
        if ($this->getRootDir() === '/vagrant/app' && in_array($this->environment, array('dev', 'test'))) {
            return '/dev/shm/appname/cache/' .  $this->environment;
        }

        return parent::getCacheDir();
    }
    /**
     * Custom log dir if dev/test environment to improve vagrant performance.
     *
     * @return string
     */
    public function getLogDir()
    {
        if ($this->getRootDir() === '/vagrant/app' && in_array($this->environment, array('dev', 'test'))) {
            return '/dev/shm/appname/logs';
        }

        return parent::getLogDir();
    }
}
