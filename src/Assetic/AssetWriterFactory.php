<?php

namespace Spiffy\AsseticModule\Assetic;

use Assetic\AssetWriter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AssetWriterFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $services
     * @return AssetWriter
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /** @var \Spiffy\AsseticModule\ModuleOptions $options */
        $options = $services->get('Spiffy\AsseticModule\ModuleOptions');

        return new AssetWriter($options->getOutputDir());
    }
}
