<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        //$serviceLocator = $this->getServiceLocator();
        $config = $this->serviceLocator->get('config');

        $view = new ViewModel();

        $view->setVariables([
                    'version'=> $config['application']['version'],
                    'applicationName' => $config['application']['name']
                ]);

        $projectsSidebarView = new ViewModel();
        $projectsSidebarView->setTemplate('company/projects');

        $view->addChild($projectsSidebarView, 'projectsSidebar');

        return $view;
    }

    public function aboutAction()
    {
        return [];
    }

    public function privacyAction()
    {
        return [];
    }
}
