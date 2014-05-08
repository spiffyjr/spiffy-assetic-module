<?php

namespace Spiffy\AsseticModule\Plugin;

use Spiffy\Assetic\AsseticService;
use Spiffy\Event\Event;
use Spiffy\Event\Manager;
use Spiffy\Event\Plugin;
use Zend\ModuleManager\ModuleManager;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class ResolveAliasPlugin implements Plugin, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @var array
     */
    protected $pathCache = [];

    /**
     * {@inheritDoc}
     */
    public function plug(Manager $events)
    {
        $events->on(AsseticService::EVENT_RESOLVE_ALIAS, [$this, 'onResolveAlias']);
    }

    /**
     * @param Event $e
     * @return string
     */
    public function onResolveAlias(Event $e)
    {
        return $this->convertModuleInput($e->getTarget());
    }

    /**
     * @param string $input
     * @return string
     */
    protected function convertModuleInput($input)
    {
        if ('@' != $input[0] || false == strpos($input, '/')) {
            return $input;
        }

        $moduleName = substr($input, 1);
        if (false !== $pos = strpos($moduleName, '/')) {
            $moduleName = substr($moduleName, 0, $pos);
        }

        return str_replace('@' . $moduleName, $this->getModulePath($moduleName), $input);
    }

    /**
     * @param string $moduleName
     * @return string
     */
    protected function getModulePath($moduleName)
    {
        if (isset($this->pathCache[$moduleName])) {
            return $this->pathCache[$moduleName];
        }

        $module = $this->getServiceLocator()->get('ModuleManager')->getModule($moduleName);
        $refl = new \ReflectionClass($module);
        $path = dirname($refl->getFileName());

        if (!file_exists($path . '/src')) {
            $path = realpath($path . '/../../');
        }

        $this->pathCache[$moduleName] = $path;
        return $path;
    }
}
