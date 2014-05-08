<?php

namespace Spiffy\AsseticModule\Plugin;

use Assetic\Cache\ConfigCache;
use Assetic\Extension\Twig\TwigFormulaLoader;
use Assetic\Extension\Twig\TwigResource;
use Assetic\Factory\LazyAssetManager;
use Assetic\Factory\Loader\CachedFormulaLoader;
use Spiffy\Assetic\AsseticService;
use Spiffy\Event\Event;
use Spiffy\Event\Manager;
use Spiffy\Event\Plugin;
use Symfony\Component\Finder\Finder;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use ZfcTwig\Twig\MapLoader;

class TwigLoaderPlugin implements Plugin, ServiceLocatorAwareInterface
{
    use ServiceLocatorAwareTrait;

    /**
     * @param Manager $events
     */
    public function plug(Manager $events)
    {
        $events->on(AsseticService::EVENT_LOAD, [$this, 'onLoad']);
    }

    /**
     * @param Event $e
     */
    public function onLoad(Event $e)
    {
        $twig = $this->getServiceLocator()->get('Twig_Environment');

        /** @var \Twig_Loader_Chain $loader */
        $chain = $twig->getLoader();
        $refl = new \ReflectionClass($chain);
        $loaders = $refl->getProperty('loaders');
        $loaders->setAccessible(true);
        $loaders = $loaders->getValue($chain);

        /** @var \Spiffy\Assetic\AsseticService $service */
        $service = $e->getTarget();
        $am = $service->getAssetManager();

        if (!$am instanceof LazyAssetManager) {
            return;
        }

        $formulaLoader = new CachedFormulaLoader(
            new TwigFormulaLoader($twig),
            new ConfigCache('data/cache/assetic'),
            $am->isDebug()
        );
        $am->setLoader('twig', $formulaLoader);

        $finder = new Finder();
        $finder
            ->files()
            ->ignoreUnreadableDirs()
            ->name('*.twig');

        $count = 0;
        foreach ($loaders as $loader) {
            if ($loader instanceof \Twig_Loader_Filesystem) {
                $finder->in($loader->getPaths());
            } else if ($loader instanceof MapLoader) {
                $refl = new \ReflectionClass($loader);
                $map = $refl->getProperty('map');
                $map->setAccessible(true);
                $map = $map->getValue($loader);

                foreach ($map as $name => $path) {
                    $count++;
                    $am->addResource(new TwigResource($loader, $name), 'twig');
                }
            }
        }

        /** @var \Symfony\Component\Finder\SplFileInfo */
        foreach ($finder as $template) {
            $count++;
            $am->addResource(new TwigResource($loader, $template->getRelativePathname()), 'twig');
        }
    }
}
