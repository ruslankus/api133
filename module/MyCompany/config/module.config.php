<?php
use MyCompany\Factory\UserServiceFactory;
use MyCompany\Service\UserService;

return array(

    'service_manager' => array(

        'factories' => array(
            UserService::class => UserServiceFactory::class
        )
    ),

    'view_manager' => array(
        'template_map' => array(
            'MyCompany/mail/user/signup' => __DIR__  . '/../view/my-company/mail/user/signup.phtml',
            'MyCompany/mail/user/forgot-password' => __DIR__  . '/../view/my-company/mail/user/forgot-password.phtml'
        )
    )

);