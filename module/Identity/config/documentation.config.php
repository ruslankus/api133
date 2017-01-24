<?php
return array(
    'Identity\\V1\\Rest\\User\\Controller' => array(
        'collection' => array(
            'POST' => array(
                'request' => '{
   "emailAddress": "Email Address",
   "password": "A user password"
}',
                'response' => '{
   "_links": {
       "self": {
           "href": "/user[/:email]"
       }
   }
   "emailAddress": "Email Address",
   "password": "A user password"
}',
            ),
            'description' => 'Register a new user',
        ),
        'description' => 'User service - handles basic user related functionality',
    ),
    'Identity\\V1\\Rest\\BeginPasswordReset\\Controller' => array(
        'description' => 'Begins the password reset process by sending a reset token to the registered email address if there is a valid account.',
        'collection' => array(
            'POST' => array(
                'description' => 'Requires a valid email address to send in a a password reset',
                'request' => '{
   "emailAddress": "Email Address"
}',
                'response' => '{
   "_links": {
       "self": {
           "href": "/account/begin-password-reset[/:begin_password_reset_id]"
       }
   }
   "emailAddress": "Email Address"
}',
            ),
        ),
    ),
);
