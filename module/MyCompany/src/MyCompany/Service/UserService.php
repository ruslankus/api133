<?php
/**
 * Created by PhpStorm.
 * User: ruslan
 * Date: 15/01/17
 * Time: 01:24
 */

namespace MyCompany\Service;


use Doctrine\ORM\EntityManagerInterface;

class UserService implements UserServiceInterface
{

    protected $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function registerUser($emailAddress, $password)
    {
        // TODO: Implement registerUser() method.
    }

    public function forgotPassword($emailAddress)
    {
        // TODO: Implement forgotPassword() method.
    }

    public function resetPassword($emailAddress, $resetToken)
    {
        // TODO: Implement resetPassword() method.
    }

    public function fetchUser($email)
    {
        // TODO: Implement fetchUser() method.
    }
}