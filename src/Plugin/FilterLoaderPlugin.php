<?php

namespace Spiffy\AsseticModule\Plugin;

use Spiffy\Assetic\AsseticService;
use Spiffy\Event\Event;
use Spiffy\Event\Manager;
use Spiffy\Event\Plugin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class FilterLoaderPlugin implements Plugin, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var bool
     */
    protected $loaded = false;

    /**
     * @param Manager $events
     */
    public function plug(Manager $events)
    {
        $events->on(AsseticService::EVENT_LOAD, [$this, 'onLoad'], 10000);
    }

    /**
     * @param Event $event
     */
    public function onLoad(Event $event)
    {
        if ($this->loaded) {
            return;
        }

        $this->loaded = true;

        /** @var \Assetic\FilterManager $filterManager */
        $filterManager = $event->getTarget()->getFilterManager();

        $services = $this->getServiceLocator();

        /** @var \Spiffy\AsseticModule\ModuleOptions $options */
        $options = $services->get('Spiffy\AsseticModule\ModuleOptions');

        foreach ($options->getFilters() as $name => $filter) {
            if (empty($filter)) {
                continue;
            }
            if (is_string($filter)) {
                $filter = $services->has($filter) ? $services->get($filter) : new $filter();
            }
            $filterManager->set($name, $filter);
        }
    }
}
