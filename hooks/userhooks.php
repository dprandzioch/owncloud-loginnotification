<?php

namespace OCA\LoginNotification\Hooks;

class UserHooks {

    private $userSession;

    public function __construct($userSession){
        $this->userSession = $userSession;
    }

    public function register() {
        $callback = function($username, $password) {
            if (strpos($_SERVER["REQUEST_URI"], 'api') !== false) {
                // @todo save request ip and allow current ip for 24 hours and remove this stuff
#                return;
            }

            $mailer = \OC::$server->getMailer();
            $message = $mailer->createMessage();
            
            $message->setSubject("Someone tried to log into your ownCloud instance");
            $message->setFrom([ "owncloud@davd.eu" => "ownCloud Notifier" ]);
            $message->setTo([ "kontakt@davidprandzioch.de" => "David Prandzioch" ]);
            $message->setPlainBody('Someone tried to log in to your ownCloud instance. Username: ' . $username . "\n\n" . print_r($_SERVER, true));
            $mailer->send($message);
        };
        
        $this->userSession->listen('\OC\User', 'preLogin', $callback);
    }

}
