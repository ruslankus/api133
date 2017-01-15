<?php
use MyCompany\Factory\UserServiceFactory;
use MyCompany\Service\UserService;

return array(

    'service_manager' => array(

        'factories' => array(
            UserService::class => UserServiceFactory::class
        )
    )

);