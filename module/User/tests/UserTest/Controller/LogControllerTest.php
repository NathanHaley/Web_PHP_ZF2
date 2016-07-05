<?php
namespace User\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Authentication\Result;
use User\Model\Entity\User;

class LogControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    
    public function setUp()
    {
        $this->setApplicationConfig(
            include './config/application.config.php'
            );
        parent::setUp();
    }
    
    public function testLogInFormCanBeAccessed()
    {
        $this->dispatch('/user/log/in');
        $this->assertResponseStatusCode(200);
        
        $this->assertModuleName('User');
        $this->assertControllerName('User\Controller\Log');
        $this->assertControllerClass('LogController');
        $this->assertMatchedRouteName('user/default');
    }
    
    public function testLogInSuccessRouteAfterValidPost()
    {     
        $adapterMock = $this->getMockBuilder('User\Authentication\Adapter')
                        ->disableOriginalConstructor()
                        ->getMock();
       
        
        $user = new User();
        $result = new Result(Result::SUCCESS, $user);
        
        //$user->setName('Test test');
        $adapterMock->expects($this->once())
                        ->method('authenticate')
                        ->will($this->returnValue($result));
        
                      
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('auth-adapter', $adapterMock);
        
        //Shouldn't matter since forcing isValid === true but will use for now.
        $postData = array(
            'username'  => 'demouser@nathanhaley.com',
            'password' => 'pass1234',
        );
        
        $this->dispatch('/user/log/in', 'POST', $postData);
        $this->assertResponseStatusCode(302);
        
        $this->assertRedirectTo('/user/account/me');
        
        
    }
    
    public function testLogInFailRouteAfterValidPost()
    {
        $adapterMock = $this->getMockBuilder('User\Authentication\Adapter')
        ->disableOriginalConstructor()
        ->getMock();
         
    
        $user = new User();
        $result = new Result(Result::FAILURE, $user);
    
        //$user->setName('Test test');
        $adapterMock->expects($this->once())
        ->method('authenticate')
        ->will($this->returnValue($result));
    
    
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('auth-adapter', $adapterMock);
    
        //Shouldn't matter since forcing isValid === true but will use for now.
        $postData = array(
            'username'  => 'demouser@nathanhaley.com',
            'password' => 'pass1234',
        );
    
        $this->dispatch('/user/log/in', 'POST', $postData);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('User');
        $this->assertControllerName('User\Controller\Log');
        $this->assertControllerClass('LogController');
    
        $this->assertActionName('in'); //should stay on log in page
    
    
    }
}
