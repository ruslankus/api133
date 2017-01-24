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
use Zend\Crypt\Password\Bcrypt;
use Zend\Mime\Part;
use Zend\View\Model\ViewModel;
use Zend\View\Renderer\RendererInterface;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;

class UserService implements UserServiceInterface
{

    const ERROR_USER_EXIST_CODE = 2;
    const ERROR_USER_EXIST_MSG = "User with such email is allready exist";

    const ERROR_USER_NOT_FOUND_CODE = 3;
    const ERROR_USER_NOT_FOUND_MSG = "Unuble to locate a user with the provided parameters";

    const ERROR_INVALD_RESET_TOKEN_CODE = 4;
    const ERROR_UINVALD_RESET_TOKEN_MSG = "Invalid reset token";




    protected $entityManager;

    protected $mailTemplateRenderer;

    protected $mailService;

    public function __construct(EntityManagerInterface $em, RendererInterface $mailTemplateRenderer, SmtpTransport $mailService )
    {
        $this->entityManager = $em;
        $this->mailTemplateRenderer = $mailTemplateRenderer;
        $this->mailService = $mailService;
    }

    protected function _getActivationCode(User $useObj)
    {
        return hash('sha256', $useObj->getId().$useObj->getEmail().$useObj->getPassword().$useObj->getCreatedAt()->getTimestamp());
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

        $bcrypt = new Bcrypt();
        $bcrypt->setCost(14);
        $userObj->setPassword($bcrypt->create($password));

        $userObj->setRoles(array());
        $userObj->setCreatedAt(new \DateTime());
        $userObj->setIsEmailConfirmed(false);
        $userObj->setIsActivated(false);
        $this->entityManager->persist($userObj);

        $this->entityManager->flush();

        /**
         * SEND MAIL
         */
        $message = new Message();
        $message->setSubject("Welcome to My company! Please activate your account");

        $viewContent = new ViewModel(array(
            'activationAccount' => 'http://localhost/user-activate/'.urlencode($userObj->getEmail()) . "/" .
                $this->_getActivationCode($userObj)
        ));
        $viewContent->setTemplate('MyCompany/mail/user/signup');


        $content = $this->mailTemplateRenderer->render($viewContent);
        $message->setFrom('ruslan@prophp.eu');

        $htmlPart = new Part($content);
        $htmlPart->type = 'text/html';
        $body = new \Zend\Mime\Message();
        $body->setParts(array($htmlPart));
        $message->setTo($userObj->getEmail());
        $message->setBody($body);
        $this->mailService->send($message);

        /**
         * Return user Object
         */
        return $userObj;
    }

    public function forgotPassword($emailAddress)
    {

        $userObj = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $emailAddress]);

        if(! $userObj instanceof User ){
            throw new \RuntimeException(self::ERROR_USER_NOT_FOUND_MSG,self::ERROR_USER_NOT_FOUND_CODE);
        }

        /**
         * SEND MAIL
         */
        $message = new Message();
        $message->setSubject("Reset password");

        $viewContent = new ViewModel(array(
            'resetUrl' => 'http://localhost/user-reset-password/'.urlencode($userObj->getEmail()) . "/" .
                $this->_getActivationCode($userObj)
        ));
        $viewContent->setTemplate('MyCompany/mail/user/forgot-password');


        $content = $this->mailTemplateRenderer->render($viewContent);
        $message->setFrom('ruslan@prophp.eu');

        $htmlPart = new Part($content);
        $htmlPart->type = 'text/html';
        $body = new \Zend\Mime\Message();
        $body->setParts(array($htmlPart));
        $message->setTo($userObj->getEmail());
        $message->setBody($body);
        $this->mailService->send($message);

        return array(
            'isMailSent' => true
        );
    }

    public function resetPassword($emailAddress, $resetToken, $newPasword)
    {
        $userObj = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $emailAddress]);

        if(! $userObj instanceof User ){
            throw new \RuntimeException(self::ERROR_USER_NOT_FOUND_MSG,self::ERROR_USER_NOT_FOUND_CODE);
        }

        $expectedResetToken = $this->_getActivationCode($userObj);

        if($expectedResetToken !== $resetToken){
            throw new \RuntimeException(self::ERROR_UINVALD_RESET_TOKEN_MSG, self::ERROR_INVALD_RESET_TOKEN_CODE);
        }

        $bcrypt = new Bcrypt();
        $bcrypt->setCost(14);
        $userObj->setPassword($bcrypt->create($newPasword));

        $this->entityManager->persist($userObj);
        $this->entityManager->flush();

        return $userObj;
    }

    public function fetchUser($emailAddress)
    {
        $userObj = $this->entityManager->getRepository(User::class)
            ->findOneBy(['email' => $emailAddress]);

        if(! $userObj instanceof User ){
            throw new \RuntimeException(self::ERROR_USER_NOT_FOUND_MSG,self::ERROR_USER_NOT_FOUND_CODE);
        }

        return $userObj;
    }
}