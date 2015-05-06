<?php

namespace Spiffy\AsseticModule\Plugin;

use Spiffy\Assetic\AsseticService;
use Spiffy\Event\Event;
use Spiffy\Event\Manager;
use Spiffy\Event\Plugin;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class AssetLoaderPlugin implements Plugin, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @param Manager $events
     */
    public function plug(Manager $events)
    {
        $events->on(AsseticService::EVENT_LOAD, [$this, 'onLoad'], -1000);
    }


    /**
     * @param Event $event
     */
    public function onLoad(Event $event)
    {
        /** @var \Spiffy\Assetic\AsseticService $asseticService */
        $asseticService = $event->getTarget();
        $manager = $asseticService->getAssetManager();
        $factory = $asseticService->getAssetFactory();

        /** @var \Spiffy\AsseticModule\ModuleOptions $options */
        $options = $this->getServiceLocator()->get('Spiffy\AsseticModule\ModuleOptions');

        foreach ($options->getAssets() as $name => $asset) {
            if (!is_array($asset)) {
                continue;
            }

            $inputs = isset($asset['inputs']) ? $asset['inputs'] : [];
            $filters = isset($asset['filters']) ? $asset['filters'] : [];
            $opts = isset($asset['options']) ? $asset['options'] : [];

            $manager->set($name, $factory->createAsset($inputs, $filters, $opts));
        }
    }
}
