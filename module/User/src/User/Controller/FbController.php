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

        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        if (! isset($accessToken)) {
            if ($helper->getError()) {
                header('HTTP/1.0 401 Unauthorized');
                echo "Error: " . $helper->getError() . "\n";
                echo "Error Code: " . $helper->getErrorCode() . "\n";
                echo "Error Reason: " . $helper->getErrorReason() . "\n";
                echo "Error Description: " . $helper->getErrorDescription() . "\n";
            } else {
                header('HTTP/1.0 400 Bad Request');
                echo 'Bad request';
            }
            exit;
        }

        // Logged in
        //echo '<h3>Access Token</h3>';
        //var_dump($accessToken->getValue());

        // The OAuth 2.0 client handler helps us manage access tokens
        $oAuth2Client = $fb->getOAuth2Client();

        // Get the access token metadata from /debug_token
        $tokenMetadata = $oAuth2Client->debugToken($accessToken);
        //echo '<h3>Metadata</h3>';
        //var_dump($tokenMetadata);

        // Validation (these will throw FacebookSDKException's when they fail)
        $tokenMetadata->validateAppId($fbConfig['app_id']); // Replace {app-id} with your app id
        // If you know the user ID this access token belongs to, you can validate it here
        //$tokenMetadata->validateUserId('123');
        $tokenMetadata->validateExpiration();

        if (! $accessToken->isLongLived()) {
            // Exchanges a short-lived access token for a long-lived one
            try {
                $accessToken = $oAuth2Client->getLongLivedAccessToken($accessToken);
            } catch (Facebook\Exceptions\FacebookSDKException $e) {
                echo "<p>Error getting long-lived access token: " . $helper->getMessage() . "</p>\n\n";
                exit;
            }

            //echo '<h3>Long-lived</h3>';
            //var_dump($accessToken->getValue());
        }

        $_SESSION['fb_access_token'] = (string) $accessToken;

        // User is logged in with a long-lived access token.
        // You can redirect them to a members-only page.
        //header('Location: https://example.com/members.php');

        try {
            // Returns a `Facebook\FacebookResponse` object
            $response = $fb->get('/me?fields=id,name,email', $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $fbUser = $response->getGraphUser();

        //@todo handle no email situations and errors
        $entityManager = $this->serviceLocator->get('entity-manager');
        $userEntityClassName = get_class($this->serviceLocator->get('user-entity'));

        //User with logged in local account
        if ($localUser = $this->identity()) {
            $entity = $this->findUserEntity($localUser->getId());

        // Not logged in but look for user account by email returned from Facebook
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
//var_dump($entity);
//$this->shareProgressOnFacebook($fb, $accessToken);


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
            $this->flashMessenger()->addErrorMessage(sprintf('Error! Could not create account. Be sure to allow access to your Facebook email address.'));
            $event = new EventManager('user');
            $event->trigger('log-fail', $this, array('Facebook Name'=> $fbUser->getName()));

            //show page
            return [];//array('errors' => $result->getMessages());

            $logOutUrl = $helper->getLogoutUrl($accessToken, 'http://localhost.phptc.com/user/log/out');

            return ['user' => $entity, 'logOutLink' => '<a href="' . htmlspecialchars($logOutUrl) . '">Log out with Facebook!</a>'];
        }

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
