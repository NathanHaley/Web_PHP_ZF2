<?php
/**
 * init and onBootstrap functions used to register event listners. Keep light as possible
 * , ie. don't instantiate resources not needed on every execution.
 * Use Service manager for that like a database resource.
 *
 * If module needs to create directories or files to write/cache data for example, write
 * them outside of the module directory tree like <root>/data/ to prevent composure or similar
 * tools from removing or overwriting.
 */
namespace Debug;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleEvent;
use Zend\EventManager\Event;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\ViewModel;


class Module implements AutoloaderProviderInterface
{

    public function errorLogWrite($message = '', $delimiter = "\n***************\n")
    {
        error_log($delimiter.$message.$delimiter);
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'handleError'));

        //Setup monitoring time of execution by request
        $serviceManager = $e->getApplication()->getServiceManager();
        $timer = $serviceManager->get('timer');
        $timer->start('mvc-execution');

        $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'injectViewVariables'), 100);

        $eventManager->attach(MvcEvent::EVENT_FINISH, array($this, 'getMvcDuration'),2);

        $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'addDebugOverlay'), 100);

        $eventManager->attach(MvcEvent::EVENT_FINISH, array($this, 'dbProfilerStats'),2);

    }

    public function dbProfilerStats(MvcEvent $event)
    {
        $services = $event->getApplication()->getServiceManager();
        if($services->has('database-profiler')) {
            $profiler = $services->get('database-profiler');
            foreach ($profiler->getProfiles() as $profile) {
                $message= '"'.$profile['sql'].'('.implode(',',$profile['parameters']->getNamedArray()).')" took '.$profile['elapse'].' seconds'."\n";

                $this->errorLogWrite($message);
            }
        }
    }

    /**
     * Injects common variable in the view model
     * @param MvcEvent $event
     */
    public function injectViewVariables(MvcEvent $event)
    {
        $viewModel = $event->getViewModel();

        $services = $event->getApplication()->getServiceManager();
        $variables = array();
        if($services->has('database-profiler')) {
            $profiler = $services->get('database-profiler');
            $variables['profiler'] = $profiler;
        }
        if(!empty($variables)) {
            $viewModel->setVariables($variables);
        }
    }


    public function addDebugOverlay(MvcEvent $event)
    {
        $viewModel = $event->getViewModel();

        $sidebarView = new ViewModel();
        $sidebarView->setTemplate('debug/layout/sidebar');
        $sidebarView->addChild($viewModel, 'content');

        $event->setViewModel($sidebarView);
    }

    public function getMvcDuration(MvcEvent $event)
    {
        $serviceManager = $event->getApplication()->getServiceManager();
        $timer = $serviceManager->get('timer');
        $duration = $timer->stop('mvc-execution');

        $this->errorLogWrite("MVC Duration:".$duration." seconds");
    }

    public function handleError(MvcEvent $event)
    {
        $controller = $event->getController();
        $error = $event->getParam('error');
        $exception = $event->getParam('exception');
        $message = sprintf('Error dispatching controller "%s". Error was: "%s"', $controller, $error);
        if ($exception instanceof \Exception) {
            $message .= ', Exception('.$exception->getMessage().'): '.$exception->getTraceAsString();
        }

        $this->errorLogWrite($message);
    }
    /**
     * Added to attach listener for logging loaded modules
     * @param ModuleManager $moduleManager
     */
    public function init(ModuleManager $moduleManager)
    {
        $eventManager = $moduleManager->getEventManager();
        $eventManager->attach(ModuleEvent::EVENT_LOAD_MODULES_POST, array($this, 'loadedModulesInfo'));
    }

    /**
     * Callback for registered listener to allow logging of loaded modules
     * @param Event $event
     */
    public function loadedModulesInfo(Event $event)
    {
        $moduleManager = $event->getTarget();
        $loadedModules = $moduleManager->getLoadedModules();
        $this->errorLogWrite("Loaded modules:\n".var_export($loadedModules, true));
    }
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
            // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/' , __NAMESPACE__),
                ),
            ),
        );
    }
}
