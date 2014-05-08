<?php

namespace Spiffy\AsseticModule\Filter;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class CssModulePathFilterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $services
     * @return CssModulePathFilter
     */
    public function createService(ServiceLocatorInterface $services)
    {
        return new CssModulePathFilter($services->get('Spiffy\Assetic\AsseticService'));
    }
}
