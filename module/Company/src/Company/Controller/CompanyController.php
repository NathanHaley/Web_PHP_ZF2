<?php

namespace Company\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class CompanyController extends AbstractActionController {
 public function indexAction() {
 }
 public function listAction() {
  $viewModel = new ViewModel ();
  $viewModel->setVariables ( array (
    'key' => 'value' 
  ) )->setTerminal ( true );
  
  return $viewModel;
 }
 public function detailAction() {
  $viewModel = new ViewModel ();
  $viewModel->setVariables ( array (
    'key' => 'value' 
  ) )->setTerminal ( true );
  
  return $viewModel;
 }
 public function projectsAction() {
  
 }
 public function helloAction() {
 }
 public function todoAction() {
 }
}

