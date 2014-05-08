<?php

namespace Spiffy\AsseticModule\Controller;

use Spiffy\Assetic\AsseticService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConsoleControllerFactory implements FactoryInterface
{
    /**
     * @param ServiceLocatorInterface $controllerManager
     * @return ConsoleController
     */
    public function createService(ServiceLocatorInterface $controllerManager)
    {
        /** @var \Zend\Mvc\Controller\ControllerManager $controllerManager */
        $services = $controllerManager->getServiceLocator();

        /** @var \Spiffy\Assetic\AsseticService $asseticService */
        $asseticService = $services->get('Spiffy\Assetic\AsseticService');

        /** @var \Spiffy\AsseticModule\ModuleOptions $options */
        $options = $services->get('Spiffy\AsseticModule\ModuleOptions');

        $this->injectPlugins($services, $asseticService, $options->getConsolePlugins());

        // this is done onRender() but that's not early enough for console
        $asseticService->load();

        return new ConsoleController($asseticService, $options);
    }

    /**
     * @param ServiceLocatorInterface $services
     * @param AsseticService $service
     * @param array $plugins
     */
    protected function injectPlugins(ServiceLocatorInterface $services, AsseticService $service, array $plugins)
    {
        foreach ($plugins as $plugin) {
            if (is_string($plugin)) {
                $plugin = $services->has($plugin) ? $services->get($plugin) : new $plugin();
            }

            $service->events()->plug($plugin);
        }
    }
}
