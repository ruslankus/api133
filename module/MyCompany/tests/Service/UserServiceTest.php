<?php
namespace MyCompany\Service;

use MyCompany\Bootstrap;
use MyCompany\Entity\User;
use MyCompany\Service\UserService;
use PHPUnit_Framework_TestCase;

require_once(__DIR__ . '/../bootstrap.php');




class UserServiceTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var $userService UserService
     */
    private $userService;


    protected function getORM()
    {
        $sm = Bootstrap::getServiceManager();
        $orm = $sm->get('doctrine.entitymanager.orm_default');
        return $orm;
    }

    protected function setUp()
    {
        parent::setUp();

        Bootstrap::init();

        $this->userService = Bootstrap::getServiceManager()->get(UserService::class);

        $orm = $this->getORM();
        $qb = $orm->createQueryBuilder();
        $exp = $qb->expr();
        $qb->select('u');
        $qb->from(User::class,'u');
        $qb->andWhere($exp->like('u.email', ':email'));
        $qb->setParameter('email','%ruslankus%');
        $iterateResult = $qb->getQuery();
        $res = $iterateResult->iterate();
        foreach ($res as $usAsArr){
            $orm->remove($usAsArr[0]);
        }
        $orm->flush();
        $orm->clear();



    }


    protected function tearDown()
    {
        $this->userService = null;

        parent::tearDown();
    }

    public function __construct()
    {

    }


    public function test__construct()
    {
        $this->assertInstanceOf(UserService::class, $this->userService);
    }


    public function testRegisterUser()
    {
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userObj = $this->userService->registerUser($email,$password);
        $this->assertInstanceOf(User::class,$userObj);
    }


    public function testRegisterUserMailAlreadyExistsException()
    {
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userObj = $this->userService->registerUser($email,$password);

        $this->assertInstanceOf(User::class,$userObj);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage(UserService::ERROR_USER_EXIST_MSG);
        $userObj = $this->userService->registerUser($email,$password);

    }

    public function testForgotPassword()
    {
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userObj = $this->userService->registerUser($email,$password);
        $this->assertInstanceOf(User::class,$userObj);

        $responce = $this->userService->forgotPassword($email);

        $this->assertInternalType('array',$responce);
        $this->assertArrayHasKey('isMailSent',$responce);
        $this->assertTrue($responce['isMailSent']);
    }

    public function testResetPassword()
    {

        //first need to reister a new user
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userObj = $this->userService->registerUser($email,$password);
        $this->assertInstanceOf(User::class,$userObj);
        //after regiteration try yo change password

        $newPassword = 'def4567';

        $stringToHash = $userObj->getId() .
            $userObj->getEmail() .
            $userObj->getPassword() .
            $userObj->getCreatedAt()->getTimestamp();
        $resetToken = hash('sha256',$stringToHash);


        $userResetObj = $this->userService->resetPassword($email,$resetToken, $newPassword);
        $this->assertInstanceOf(User::class, $userResetObj);

    }

    public function testFetchUser()
    {
        $email = 'ruslankus@yahoo.com';
        $password = 'abc1234';
        $userObj = $this->userService->registerUser($email,$password);
        $this->assertInstanceOf(User::class,$userObj);

        $expectedOutput = $this->userService->fetchUser($email);
        $this->assertInstanceOf(User::class, $expectedOutput);

    }

}
