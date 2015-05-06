<?php

namespace Spiffy\AsseticModule\Controller;

use Spiffy\Assetic\AsseticService;
use Spiffy\AsseticModule\ModuleOptions;
use Spiffy\Event\Event;
use Zend\Console\ColorInterface;
use Zend\Mvc\Controller\AbstractActionController;

class ConsoleController extends AbstractActionController
{
    /**
     * @param AsseticService $asseticService
     * @param ModuleOptions $options
     */
    public function __construct(AsseticService $asseticService, ModuleOptions $options)
    {
        $this->asseticService = $asseticService;
        $this->options = $options;

        $events = $asseticService->events();
        $events->on(AsseticService::EVENT_DUMP_DIR, [$this, 'onDumpDir']);
        $events->on(AsseticService::EVENT_DUMP_TARGET, [$this, 'onDumpTarget']);
    }

    public function dumpAction()
    {
        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');
        $am = $this->getAssetManager();

        $filename = $this->getEvent()->getRouteMatch()->getParam('filename');

        // print the header
        if ($filename) {
            $console->write('Dumping ');
            $console->writeLine($filename, ColorInterface::YELLOW);
        } else {
            $console->writeLine('Dumping all assets.');
        }

        $console->write('Debug mode is ');
        $console->writeLine($am->isDebug() ? 'on' : 'off', ColorInterface::YELLOW);
        $console->writeLine('');

        /** @var \Zend\Console\Request $request */
        $request = $this->getRequest();

        if ($filename) {
            $this->asseticService->dumpAsset(
                $filename,
                $this->options->getOutputDir(),
                $this->options->getVariables(),
                $request->getParam('verbose')
            );
        } else {
            $this->asseticService->dumpAssets(
                $this->options->getOutputDir(),
                $this->options->getVariables(),
                $request->getParam('verbose')
            );
        }
    }

    public function watchAction()
    {
        $services = $this->getServiceLocator();

        /** @var \Zend\Console\Request $request */
        $request = $this->getRequest();

        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');
        $am = $this->getAssetManager();

        /** @var \Spiffy\AsseticModule\ModuleOptions $options */
        $options = $services->get('Spiffy\AsseticModule\ModuleOptions');

        // print the header
        $console->writeLine(sprintf('Dumping all assets.'));
        $console->write('Debug mode is ');
        $console->writeLine($am->isDebug() ? 'on' : 'off', ColorInterface::YELLOW);
        $console->writeLine('');

        $this->asseticService->watchAssets(
            $options->getOutputDir(),
            $request->getParam('force'),
            $request->getParam('period'),
            $options->getVariables(),
            $request->getParam('verbose')
        );
    }

    /**
     * @return \Assetic\Factory\LazyAssetManager
     */
    public function getAssetManager()
    {
        return $this->asseticService->getAssetManager();
    }

    /**
     * @param Event $e
     */
    public function onDumpDir(Event $e)
    {
        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');
        $dir = $e->getTarget();

        $console->write(date('H:i:s'), ColorInterface::GREEN);
        $console->write('  ');
        $console->write('[dir+]', ColorInterface::YELLOW);
        $console->write(' ');
        $console->writeLine($dir);
    }

    /**
     * @param Event $e
     */
    public function onDumpTarget(Event $e)
    {
        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');
        $target = $e->getTarget();

        $console->write(date('H:i:s'), ColorInterface::GREEN);
        $console->write(' ');
        $console->write('[file+]', ColorInterface::YELLOW);
        $console->write(' ');
        $console->writeLine($target);
    }

    /**
     * @param Event $e
     */
    public function onDumpAsset(Event $e)
    {
        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');

        /** @var \Assetic\Asset\AssetInterface $asset */
        $asset = $e->getTarget();
        $root = $asset->getSourceRoot();
        $path = $asset->getSourcePath();

        $console->writeLine(sprintf(
            '        %s/%s',
            $root ?: '[unknown root]',
            $path ?: '[unknown path]'
        ), ColorInterface::GREEN);
    }

    /**
     * @param Event $e
     */
    public function onWatchError(Event $e)
    {
        /** @var \Zend\Console\Adapter\AdapterInterface $console */
        $console = $this->getServiceLocator()->get('console');

        $ex = $e->getTarget();
        $console->writeLine('[error] ' . $ex->getMessage(), ColorInterface::WHITE, ColorInterface::RED);
    }
}
