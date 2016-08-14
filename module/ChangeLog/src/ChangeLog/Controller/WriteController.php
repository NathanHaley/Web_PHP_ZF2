<?php
namespace ChangeLog\Controller;

use ChangeLog\Service\ChangeLogServiceInterface;
use Zend\Form\FormInterface;
use Util\Controller\UtilBaseController;

class WriteController extends UtilBaseController
{
    protected $changeLogService;
    
    protected $changeLogForm;
    
    public function __construct(
        ChangeLogServiceInterface $changeLogService,
        FormInterface $changeLogForm
    ) {
        $this->changeLogService = $changeLogService;
        $this->changeLogForm    = $changeLogForm;
    }
    
    public function addAction()
    {
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            $this->changeLogForm->setData($request->getPost());
            
            if ($this->changeLogForm->isValid()) { 
                try {
                    $config = $this->serviceLocator->get('config');
                    $dateTimeFormat = $config['application']['date_time_format'];
                     
                    $changeLogObject = $this->changeLogForm->getData();
                    
                    $userId = $this->identity()->getId();
                    
                    $changeLogObject->setAUserId($userId);
                    $changeLogObject->setAddId($userId);
                    $changeLogObject->setStatId(10);
                                      
                    $this->changeLogService->saveChangeLog($changeLogObject);
                    
                    $this->flashMessengerMulti(['Change log added.'], 'success');
                                     
                    return $this->redirect()->toRoute('changelog');
                } catch (\Exception $e) {
                    $this->flashMessengerMulti(['Try again later.','Error code: '.$e->getLine(), 'Error file: '.$e->getFile(), 'Error messege: '.$e->getMessage()], 'error');
                    return $this->redirect()->toRoute('changelog');
                }
            }
        }
        
        return [
            'form' => $this->changeLogForm  
        ];
    }
    
    public function editAction()
    {
        if ($id = $this->params()->fromRoute('id')) {
            
            $changeLog = $this->changeLogService->fetchChangeLog($id);
    
            $this->changeLogForm->bind($changeLog);
        }
    
        $request = $this->getRequest();
        
        if ($request->isPost()) { 
                       
            $id = intval($request->getPost()->get('changeLog-fieldset')['id']);
            
            $changeLog = $this->changeLogService->fetchChangeLog($id);
            
            $this->changeLogForm->bind($changeLog);
            
            $this->changeLogForm->setData($request->getPost());
    
            if ($this->changeLogForm->isValid()) {
                try {
                    
                    $changeLogObject = $this->changeLogForm->getData();
                    
                    $userId = $this->identity()->getId();
                    
                    $changeLogObject->setModId($userId);
                    $changeLogObject->setModTs();
                    $changeLogObject->setStatId(11);
                    
                    $this->changeLogService->saveChangeLog($changeLogObject);
                    
                    $this->flashMessengerMulti(['Change log saved.'], 'success');
                    return $this->redirect()->toRoute('changelog');
                } catch (\Exception $e) {
                    
                    var_dump($e->getTrace());
                    $this->flashMessengerMulti(['Try again later.','Error code: '.$e->getLine(),'Error messege: '.$e->getMessage()], 'error');
                    return $this->redirect()->toRoute('changelog');
                }
            }
        }
    
        return ['form' => $this->changeLogForm];
    }
}
