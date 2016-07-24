<?php
namespace User\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Facebook\Facebook;
use Facebook\FacebookResponse;

class FbController extends AbstractActionController
{

    public function indexAction()
    {
        return [];
    }

    public function fbLoginAction()
    {
        $config = $this->serviceLocator->get('config');
        $fbConfig = $config['application']['fb'];

        $fb = $this->createFacebook();

        $helper = $fb->getRedirectLoginHelper();

        $permissions = ['email','public_profile','publish_actions'];
        $loginUrl = $helper->getLoginUrl($fbConfig['login_callback'], $permissions);

        return $this->redirect()->toUrl($loginUrl);
    }

    public function fbCallbackAction()
    {

        $config = $this->serviceLocator->get('config');
        $fbConfig = $config['application']['fb'];

        $fb = $this->createFacebook();

        $helper = $fb->getRedirectLoginHelper();


        $FbCommunicationIssueMsgs = [
            'Sorry! We could not get Facebook login to work for some reason.',
            'Try again later or Sign up/in with a local account to get started now.',
            //'We have notified the Administrators of this site.',
            'Thank you for your patience.'
        ];

        // Can we talk to FB and local SDK?
        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            //@todo log and alert admin to Facebook issue
            throw new \Exception('Facebook: response exception');
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            //@todo log and alert admin to local Facebook SDK isssues
            throw new \Exception('Facebook: local SDK exception');
        } catch(\Exception $e) {
            //@todo log and alert admin
            $this->fbIssuesHandler($FbCommunicationIssueMsgs, 'error');

            return $this->redirect()->toRoute('home');
        }

        //Did we get an access token?
        try{
            if (! isset($accessToken)) {
                if ($helper->getError()) {
                    $exceptionMsg = 'HTTP/1.0 401 Unauthorized'.PHP_EOL;
                    $exceptionMsg .= "Error: " . $helper->getError().PHP_EOL;
                    $exceptionMsg .= "Error Code: " . $helper->getErrorCode().PHP_EOL;
                    $exceptionMsg .= "Error Reason: " . $helper->getErrorReason().PHP_EOL;
                    $exceptionMsg .= "Error Description: " . $helper->getErrorDescription().PHP_EOL;
                } else {
                    $exceptionMsg = 'HTTP/1.0 400 Bad Request'.PHP_EOL;
                    $exceptionMsg .= 'Bad request'.PHP_EOL;
                }
                throw new \Exception($exceptionMsg);
            }
        } catch (\Exception $e) {
            //@todo log and alert admin
            $this->fbIssuesHandler($FbCommunicationIssueMsgs, 'error');

            return $this->redirect()->toRoute('home');
        }

        // Logged in

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);

        // Validate our app id
        try {
            $tokenMetadata->validateAppId($fbConfig['app_id']);

        } catch (Exception $e) {
            //@todo log and alert admin
            $this->fbIssuesHandler($FbCommunicationIssueMsgs, 'error');

            return $this->redirect()->toRoute('home');
        }


        // If you know the user ID this access token belongs to, you can validate it here
        //@todo can I validate user fb id and access token reliably?
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                //@todo log and alert admin
                $this->fbIssuesHandler($FbCommunicationIssueMsgs, 'error');

                return $this->redirect()->toRoute('home');
            }
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;

        // User is logged in with a long-lived access token.

        //Store or update FB details, create new account with FB email

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,name,email', $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            throw new \Exception('Facebook: get(/me?fields=id,name,email) response issue');
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            throw new \Exception('Facebook: local SDK issue');
        } catch(Exception $e) {
            //@todo log and alert admin
            $this->fbIssuesHandler($FbCommunicationIssueMsgs, 'error');

            return $this->redirect()->toRoute('home');
        }

        $fbUser = $response->getGraphUser();

        //@todo handle no email situations and errors
        $entityManager = $this->serviceLocator->get('entity-manager');
        $userEntityClassName = get_class($this->serviceLocator->get('user-entity'));

        //User with logged in local account
        if ($localUser = $this->identity()) {
            $entity = $this->findUserEntity($localUser->getId());

        // Not logged locally in but look for user account by email returned from Facebook
        } elseif ($entity = $entityManager->getRepository($userEntityClassName)->findOneByEmail($fbUser->getEmail())) {

            //@todo do stuff

        } else {
            $entity = $this->serviceLocator->get('user-entity');
            $aclDefaults = $config = $this->serviceLocator->get('config')['acl']['defaults'];

            $entity->setName($fbUser->getName());

            // @todo handle dups
            $entity->setEmail($fbUser->getEmail());
            $entity->setRole($aclDefaults['member_role']);

            $entity->setPassword($accessToken->getValue());
        }

        $entity->setFb_access_token($accessToken->getValue());
        $entity->setFb_user_id($tokenMetadata->getUserId());
        $entity->setFb_access_token_expire_dt($accessToken->getExpiresAt());

        $entity = $entityManager->merge($entity);
        $entityManager->flush();

        $user = $this->logUserInByCredential($entity->getEmail(), $accessToken->getValue());

        if($user !== false) {

            $this->flashMessenger()->addSuccessMessage(sprintf('Welcome %s. You are now logged in.',$user->getName()));

            return $this->redirect()->toRoute('user/default', array (
                'controller' => 'account',
                'action'     => 'me',
            ));
        } else {
            $this->flashMessenger()->addErrorMessage('Error! Could not create account. Be sure to allow access to your Facebook email address.');
            $event = new EventManager('user');
            $event->trigger('log-fail', $this, array('Facebook Name'=> $fbUser->getName()));

            //return $this->redirect()->toRoute('home');


            $logOutUrl = $helper->getLogoutUrl($accessToken, 'http://localhost.phptc.com/user/log/out');

            return ['user' => $entity, 'logOutLink' => '<a href="' . htmlspecialchars($logOutUrl) . '">Log out with Facebook!</a>'];

        }

    }

    public function issueAction()
    {
        return [];
    }

    protected function fbIssuesHandler($messages, $type)
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

    protected function findUserEntity($id)
    {
        $entityManager = $this->serviceLocator->get('entity-manager');
        $config = $this->serviceLocator->get('config');
        $entity = $entityManager->find($config['service_manager']['invokables']['user-entity'], $id);

        return $entity;
    }

    private function createFacebook()
    {
        $config = $this->serviceLocator->get('config');
        $fbConfig = $config['application']['fb'];

        $fb = new Facebook([
            'app_id' => $fbConfig['app_id'],
            'app_secret' => $fbConfig['app_secret'],
            'default_graph_version' => $fbConfig['default_graph_version'],
        ]);

        return $fb;
    }

    public function shareProgressOnFacebook($fb, $accessToken)
    {
        $messageData = [
          'message' => 'I passed an Exam at PHPtc!',
          ];

        try {
          // Returns a `Facebook\FacebookResponse` object
          $response = $fb->post('/me/feed', $messageData, $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
          echo 'Graph returned an error: ' . $e->getMessage();
          exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
          echo 'Facebook SDK returned an error: ' . $e->getMessage();
          exit;
        } catch(Exception $e) {
            //@todo something
        }

        $graphNode = $response->getGraphNode();
    }

    protected function logUserInByCredential($username, $credential)
    {
        $auth = $this->serviceLocator->get('auth');
        $authAdapter = $auth->getAdapter();

        // below we pass the username and the password to the authentication adapter for verification
        $authAdapter->setIdentity($username);
        $authAdapter->setCredential($credential);

        // here we do the actual verification
        $result = $auth->authenticate();
        $isValid = $result->isValid();

        if($isValid) {
            // upon successful validation the getIdentity method returns
            // the user entity for the provided credentials
            $user = $result->getIdentity();

            // @todo: upon successful validation store additional information in the auth storage

            return $user;
        } else {

            return false;
        }
    }

    /*
    public function shareExamScoreOnFacebook()
    {
        $eggCount = $this->getTodaysEggCountForUser($this->getLoggedInUser());
        $facebook = $this->createFacebook();

        $ret = $this->makeApiRequest(
            $facebook,
            '/'.$facebook->getUser().'/feed',
            'POST',
            array(
                'message' => sprintf('Woh my chickens have laid %s eggs today!', $eggCount),
            )
            );

        // if makeApiRequest returns a redirect, do it! The user needs to re-authorize
        if ($ret instanceof RedirectResponse) {
            return $ret;
        }

        return $this->redirect($this->generateUrl('home'));
    }

    private function makeApiRequest(\Facebook $facebook, $url, $method, $parameters)
    {
        try {
            return $facebook->api($url, $method, $parameters);
        } catch (\FacebookApiException $e) {
            // https://developers.facebook.com/docs/graph-api/using-graph-api/#errors
            if ($e->getType() == 'OAuthException' || in_array($e->getCode(), array(190, 102))) {
                // our token is bad - reauthorize to get a new token
                return $this->redirect($this->generateUrl('facebook_authorize_start'));
            }

            // it failed for some odd reason...
            throw $e;
        }
    }
    */
}
