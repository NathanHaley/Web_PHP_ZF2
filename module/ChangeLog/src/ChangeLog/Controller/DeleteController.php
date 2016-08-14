<?php
namespace ChangeLog\Controller;

use ChangeLog\Service\ChangeLogServiceInterface;
use Zend\View\Model\ViewModel;
use Util\Controller\UtilBaseController;

class DeleteController extends UtilBaseController
{
    /**
     * @var \ChangeLog\Service\ChangeLogServiceInterface
     */
    protected $changeLogService;
    
    public function __construct(ChangeLogServiceInterface $changeLogService)
    {
        $this->changeLogService = $changeLogService;
    }
    
    public function deleteAction()
    {    
        $id = intval($this->params()->fromRoute('id'));
        try {
            $changeLog = $this->changeLogService->fetchChangeLog($id);
        } catch (\InvalidArgumentException $e) {
            $this->flashMessenger()->addErrorMessage('Try again later.');
            return $this->redirect()->toRoute('changelog');
        }
        
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $del = $request->getPost('delete_confirmation', 'no');
            
            if ($del === 'yes') {
                $this->changeLogService->deleteChangeLog($changeLog);
            }
            
            $this->flashMessenger()->addSuccessMessage('Change log deleted with id: '.$id);
            return $this->redirect()->toRoute('changelog');
        }
        
        return [ 'changeLog' => $changeLog];
    }
}

