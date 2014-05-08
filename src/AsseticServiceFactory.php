<?php

namespace Spiffy\AsseticModule;

use Spiffy\Assetic\AsseticService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AsseticServiceFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $services
     * @return AsseticService
     */
    public function createService(ServiceLocatorInterface $services)
    {
        /** @var \Spiffy\AsseticModule\ModuleOptions $options */
        $options = $services->get('Spiffy\AsseticModule\ModuleOptions');

        $service = new AsseticService($options->getRootDir(), $options->getDebug());
        $this->addPlugins($services, $service, $options->getPlugins());

        return $service;
    }

    /**
     * @param ServiceLocatorInterface $services
     * @param AsseticService $service
     * @param array $plugins
     */
    protected function addPlugins(ServiceLocatorInterface $services, AsseticService $service, array $plugins)
    {
        foreach ($plugins as $plugin) {
            if (empty($plugin)) {
                continue;
            }

            if (is_string($plugin)) {
                $plugin = $services->has($plugin) ? $services->get($plugin) : new $plugin();
            }

            $service->events()->plug($plugin);
        }
    }
}
