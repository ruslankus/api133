<?php
namespace Identity\V1\Rest\FinishPasswordReset;

use MyCompany\Service\UserService;

class FinishPasswordResetResourceFactory
{
    public function __invoke($services)
    {
        $userSrvice = $services->get(UserService::class);

        return new FinishPasswordResetResource($userSrvice);
    }
}
