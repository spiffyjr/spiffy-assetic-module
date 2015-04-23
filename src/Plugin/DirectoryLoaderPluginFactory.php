<?php

namespace Spiffy\AsseticModule\Plugin;

use Spiffy\Assetic\Plugin\DirectoryLoaderPlugin;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class DirectoryLoaderPluginFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $services
     * @return DirectoryLoaderPlugin
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /** @var \Spiffy\AsseticModule\ModuleOptions $options */
        $options = $services->get('Spiffy\AsseticModule\ModuleOptions');

        return new DirectoryLoaderPlugin(
            $options->getDirectories(),
            $options->getCacheDir()
        );
    }
}
