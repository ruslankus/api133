<?php

namespace MyCompany\Factory;


use MyCompany\Service\UserService;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplateMapResolver;

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

        $mailTransport = new Smtp();
        $smtpOtions = new SmtpOptions(array(
            'name'              => 'smtp.yandex.ru',
            'host'              => 'smtp.yandex.ru',
            'port'              => 465, // Notice port change for TLS is 587
            'connection_class'  => 'plain',
            'connection_config' => array(
                'username' => 'ruslan@prophp.eu',
                'password' => 'mn867535144',
                'ssl'      => 'ssl',
            ),
        ));
        $mailTransport->setOptions($smtpOtions);

        $mailRenderer = new PhpRenderer();
        $resolver = new TemplateMapResolver();
        $resolver->setMap($serviceLocator->get('Config')['view_manager']['template_map']);
        $mailRenderer->setResolver($resolver);

        $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $service = new UserService($em,$mailRenderer,$mailTransport);

        return $service;
    }
}