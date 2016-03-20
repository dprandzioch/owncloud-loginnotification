<?php

namespace OCA\LoginNotification\AppInfo;

use OCP\AppFramework\App;
use OCA\LoginNotification\Hooks\UserHooks;


class Application extends App {

    public function __construct(array $urlParams=array()){
        parent::__construct('loginnotification', $urlParams);

        $container = $this->getContainer();

        /**
         * Controllers
         */
        $container->registerService('UserHooks', function($c) {
            $userHooks = new UserHooks(
                $c->query('ServerContainer')->getUserSession()
            );
            
            return $userHooks;
        });
    }
}