<?php

namespace  Works\UserBundle\Security\Core\User;
 
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Symfony\Component\Security\Core\User\UserInterface;
 
class FOSUBUserProvider extends BaseClass
{
 
    /**
     * {@inheritDoc}
     */
    /*public function connect(UserInterface $user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
 
        $service = $response->getResourceOwner()->getName();
 
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
 
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }

        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
 
        $this->userManager->updateUser($user);
    }*/
    
    private function setFacebook(UserResponseInterface $response)
    {
        $username = $response->getUsername();
        $useremail = $response->getEmail();
        $user = $this->userManager->findUserByEmail($useremail);

        if ($user === null) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            $user = $this->userManager->createUser();
            $user->setFacebookId($username);
            $user->setFacebookAccessToken($response->getAccessToken());
            $user->setUsername($useremail);
            $user->setEmail($useremail);
            $user->setPassword($username);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
            return $user;
        }

        $user->setFacebookAccessToken($response->getAccessToken());
 
        return $user;
    }
    
    
    /**
     * For twitter auth.
     */
    private function setTwitter(UserResponseInterface $response)
    {
        $username = $response->getNickname();
        $useremail = $response->getUsername() . "@twitter.com";
        $user = $this->userManager->findUserByEmail($useremail);

        if($user === null) {
            $user = $this->userManager->createUser();
            $user->setTwitterId($username);
            $user->setTwitterAccessToken($response->getAccessToken());
            $user->setUsername($username);
            $user->setEmail($useremail);
            $user->setPassword($username);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
            return $user;
        }

        $user->setTwitterAccessToken($response->getAccessToken());
 
        return $user;
    }
    
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $method = "set" . ucfirst($response->getResourceOwner()->getName());
        if(method_exists($this, $method)) {
            return $this->$method($response);
        } else {
            throw new \Exception('Something went wrong!');
        }
        $username = $response->getUsername();

        $user = $this->userManager->findUserByEmail($useremail);
        if ($user === null) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            $user->setUsername($useremail);
            $user->setEmail($useremail);
            $user->setPassword($username);
            $user->setEnabled(true);
            $this->userManager->updateUser($user);
            return $user;
        }
 
        $user = $this->userManager->findUserByEmail($useremail);
 
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
 
        //update access token
        $user->$setter($response->getAccessToken());
 
        return $user;
    }
}