<?php
/**
 * Created by PhpStorm.
 * User: ruslan
 * Date: 15/01/17
 * Time: 01:24
 */

namespace MyCompany\Service;


use AssetManager\Exception\RuntimeException;
use Doctrine\ORM\EntityManagerInterface;
use MyCompany\Entity\User;

class UserService implements UserServiceInterface
{

    const ERROR_USER_EXIST_CODE = 2;

    const ERROR_USER_EXIST_MSG = "User with such email is allready exist";

    protected $entityManager;

    public function __construct(EntityManagerInterface $em)
    {
        $this->entityManager = $em;
    }

    public function registerUser($emailAddress, $password)
    {
        $userObj = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $emailAddress]);

        if($userObj instanceof User){
            throw new RuntimeException(self::ERROR_USER_EXIST_MSG, self::ERROR_USER_EXIST_CODE);
        }

        $userObj = new User();
        $userObj->setEmail($emailAddress);
        $userObj->setPassword($password);
        $userObj->setRoles(array());
        $userObj->setCreatedAt(new \DateTime());
        $userObj->setIsEmailConfirmed(false);
        $userObj->setIsActivated(false);
        $this->entityManager->persist($userObj);

        $this->entityManager->flush();

        return $userObj;
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