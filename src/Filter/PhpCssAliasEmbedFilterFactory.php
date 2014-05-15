<?php

namespace Spiffy\AsseticModule\Filter;

use Spiffy\Assetic\Filter\PhpCssAliasEmbedFilter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PhpCssAliasEmbedFilterFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $services
     * @return PhpCssAliasEmbedFilter
     */
    public function createService(ServiceLocatorInterface $services)
    {
        return new PhpCssAliasEmbedFilter($services->get('Spiffy\Assetic\AsseticService'));
    }
}
