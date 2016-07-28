<?php
namespace NHUtils\Controller;

use Zend\Mvc\Controller\AbstractActionController;


class NHUtilsBaseController extends AbstractActionController
{
    /**
     * Method for creating multiple flash messages in single call
     *
     * @param  Array $messages
     * @return bool
     */
    protected function flashMessengerMulti($messages, $type)
    {
        switch ($type) {
            case 'error':
                break;
            case 'info':
                break;
            case 'success':
                break;
            case 'warning':
                break;
            default:
                return false;
        }

        $addFunc = 'add'.ucfirst($type).'Message';

        foreach ($messages as $message) {
            $this->flashMessenger()->$addFunc($message);
        }

        return true;
    }
}
