<?php

namespace Dashboard\CommonBundle\Security\Core\User;

use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Doctrine\ORM\EntityManager;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Dashboard\CommonBundle\Entity\User;
use Dashboard\CommonBundle\Entity\UserInfo;

class OAuthUserProvider implements OAuthAwareUserProviderInterface, UserProviderInterface
{
    protected $entityManager;

    protected $repository;

    protected $encoderFactory;
    
    private $response;

    public function __construct(EntityManager $entityManager, EncoderFactoryInterface $encoderFactory)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository('DashboardCommonBundle:User');
        $this->encoderFactory = $encoderFactory;
    }

    protected function createUserFromResponse(UserResponseInterface $response)
    {
        if (!$response->getUsername()) {
            throw new \RuntimeException('Unable to authenticate. An error occurred during OAuth authentication.');
        }
        
        $fs = new Filesystem();
        $settings = $this->entityManager->getRepository("DashboardCommonBundle:Settings")->find(1);
        $role = $this->entityManager->getRepository("DashboardCommonBundle:Role")->findOneByRole("ROLE_INDIVIDUAL");

        $user = new User();
        
        if($this->response->getEmail())
        {
            $user->setUsername($this->response->getEmail());
            $user->setEmail($this->response->getEmail()); 
        }
        
        $factory = $this->encoderFactory->getEncoder($user);
        $user->setPassword($factory->encodePassword($this->response->getUsername(), $user->getSalt()));
        
        $user->setIsActive(1);
        $user->addRole($role);
        $user->setAlerts(1);
        $user->setIsConfirm(1);  
        $user->setAdvertNumber(0);
        
        $userinfo = new UserInfo();
        $userinfo->setUser($user);
        
        $userName = explode(" ",$this->response->getRealName());
        
        if($userName[1] && $userName[0])
        {
            $userinfo->setFirstname($userName[1]);
            $userinfo->setLastname($userName[0]);
        }
        
        switch($this->response->getResourceOwner()->getName())
        {
            case 'facebook':
                $user->setFbID($this->response->getUsername());
                $user->setVkID(null);
            break;
        }
        
        $this->entityManager->persist($user);
        $this->entityManager->persist($userinfo);
        $this->entityManager->flush();
        
        return $user;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        $this->response = $response;
        
        try {
            $user = $this->loadUserByUsername($response->getEmail());
        } catch (UsernameNotFoundException $e) {            
            $user = $this->createUserFromResponse($response);
        }
        return $user;
    }

    public function supportsClass($class)
    {
        return $class === 'Dashboard\CommonBundle\Entity\User';
    }

    public function loadUserByUsername($username)
    {
        if($username)
        {
            switch($this->response->getResourceOwner()->getName())
            {
                case 'facebook':
                    $user = $this->repository->findOneBy(array('username' => $username, 'fbID' => $this->response->getUsername()));
                break;
            }
            
            if(null === $user)
                $user = $this->repository->findOneByUsername($username);
        }
        else
        {
            switch($this->response->getResourceOwner()->getName())
            {
                case 'facebook':
                    $user = $this->repository->findOneByFbID($this->response->getUsername());
                break;
            }
        }
        if (null === $user) 
        {
            throw new UsernameNotFoundException(sprintf('User "%s" not found.', $username));
        }
        else
        {
            switch($this->response->getResourceOwner()->getName())
            {
                case 'facebook':
                    $user->setFbID($this->response->getUsername());
                    $this->entityManager->persist($user);
                    $this->entityManager->flush();
                break;
            }
        }
        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->repository->refreshUser($user);
    }
}

