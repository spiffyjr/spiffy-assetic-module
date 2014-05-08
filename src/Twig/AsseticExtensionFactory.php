<?php

namespace Spiffy\AsseticModule\Twig;

use Spiffy\Assetic\Twig\AsseticExtension;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AsseticExtensionFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $services
     * @return AsseticExtension
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /** @var \Spiffy\Assetic\AsseticService $service */
        $service = $services->get('Spiffy\Assetic\AsseticService');
        $factory = $service->getAssetFactory();

        /** @var \Spiffy\AsseticModule\ModuleOptions $options */
        $options = $services->get('Spiffy\AsseticModule\ModuleOptions');

        return new AsseticExtension($factory, $options->getParsers());
    }
}
