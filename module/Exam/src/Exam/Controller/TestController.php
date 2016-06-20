<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Exam for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Exam\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Factory;

class TestController extends AbstractActionController
{
    public function takeAction()
    {
        $id = $this->params('id');
        if(!$id) {
            return $this->redirection()->toRoute('exam/list');
        }

        $factory = new Factory();
        $spec = include __DIR__.'/../../../config/form/form1.php';
        $form = $factory->create($spec);
        return array('form' => $form);
    }

    public function indexAction()
    {
        return array();
    }

    public function fooAction()
    {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /test/test/foo
        return array();
    }
}
