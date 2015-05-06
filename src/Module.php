<?php

namespace Spiffy\AsseticModule;

use Zend\Console\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\ModuleManager\Feature\ConsoleUsageProviderInterface;

class Module implements ConfigProviderInterface, ConsoleUsageProviderInterface
{
    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    /**
     * {@inheritDoc}
     */
    public function getConsoleUsage(AdapterInterface $console)
    {
        return [
            // Describe available commands
            'assetic dump <filename>' => 'dump assets or a single asset once',
            'assetic watch' => 'dump assets when changes are detected',
        ];
    }
}
