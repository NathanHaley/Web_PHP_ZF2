<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Exam for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Exam;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\EventManager\EventManager;

class Module implements AutoloaderProviderInterface
{
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

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event)
    {
        $services = $event->getApplication()->getServiceManager();
        $dbAdapter = $services->get('database');
        \Zend\Db\TableGateway\Feature\GlobalAdapterFeature::setStaticAdapter($dbAdapter);

        $sharedEventManager = $event->getApplication()->getEventManager()->getSharedManager();

        $sharedEventManager->attach('exam','certificate-generated', function ($event) use ($services) {
            $mail = $services->get('mail');
            $user = $event->getParam('user');
            $examId = $event->getParam('examId');
            $pdf  = $event->getParam('pdf');


            $mail->sendCertificate($user, $examId, $pdf);
        });

        $sharedEventManager->attach('exam','taken-excellent', function ($event) use ($services) {
            $user = $event->getParam('user');
            $examId = $event->getParam('examId');

            $pdf = $services->get('pdf');
            $pdfDocument = $pdf->generateCertificate($user, $examId);

            $newEvent = new EventManager('exam');
            $newEvent->trigger('certificate-generated', $this, array (
                    'user' => $event->getParam('user'),
                    'examId' => $event->getParam('examId'),
                    'pdf'  => $pdfDocument
            ));
        });


    }
}
