<?php

namespace MyCompany\Factory;


use MyCompany\Service\UserService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class UserServiceFactory implements FactoryInterface
{

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if($serviceLocator instanceof ServiceLocatorAwareInterface){
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $service = new UserService($em);

        return $service;
    }
}