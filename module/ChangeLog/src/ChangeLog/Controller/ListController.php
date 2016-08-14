<?php

namespace ChangeLog\Controller;

use Util\Controller\UtilBaseController;
use ChangeLog\Service\ChangeLogServiceInterface;

class ListController extends UtilBaseController
{
    /**
     * @var \ChangeLog\Service\ChangeLogServiceInterface
     */
    protected $changeLogService;
    
    public function __construct(ChangeLogServiceInterface $changeLogService)
    {
        $this->changeLogService = $changeLogService;
    }
    
    public function indexAction()
    { 
        return [
            'changeLogs' => $this->changeLogService->fetchAllChangeLogs()
        ];
    }   
    
    public function detailAction()
    {
        $id = $this->params()->fromRoute('id');
        
        try {
            $changeLog = $this->changeLogService->fetchChangeLog($id);
        } catch (\InvalidArgumentException $ex) {
            $this->flashMessenger()->addErrorMessage('The identifier for the Change Log was invalid.');
            return $this->redirect()->toRoute('changelog');
        }
        
        return [
            'changeLog' => $changeLog
        ];
    }
    
}