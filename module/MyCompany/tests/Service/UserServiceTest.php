<?php
namespace MyCompany\Service;

use MyCompany\Bootstrap;
use MyCompany\Service\UserService;
use PHPUnit_Framework_TestCase;

require_once(__DIR__ . '/../bootstrap.php');




class UserServiceTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var $userService UserService
     */
    private $userService;

    protected function setUp()
    {
        parent::setUp();

        Bootstrap::init();

        $this->userService = Bootstrap::getServiceManager()->get(UserService::class);
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
        $email = 'test@test.lt';
        $password = 'abc1234';
        $userObj = $this->userService->registerUser($email,$password);
        $this->assertInstanceOf(User::class,$userObj);
    }

    public function testForgotPassword()
    {

    }

    public function testResetPassword()
    {

    }

    public function testFetchUser()
    {

    }

}
