<?php

/**
 * ownCloud - loginnotification
 *
 * This file is licensed under the Affero General Public License version 3 or
 * later. See the COPYING file.
 *
 * @author David Prandzioch <kontakt@davidprandzioch.de>
 * @copyright David Prandzioch 2016
 */

namespace OCA\LoginNotification\AppInfo;

use OCP\AppFramework\App;
use OCA\LoginNotification\Hooks\UserHooks;

/**
 * Main application class
 */
class Application extends App
{

    /**
     * Sets up the applications' shared services
     * 
     * @param array $urlParams
     */
    public function __construct(array $urlParams = array())
    {
        parent::__construct('loginnotification', $urlParams);

        $container = $this->getContainer();

        $container->registerService("ItemMapper", function($c) {
            return new \OCA\LoginNotification\Db\ItemMapper(
                \OC::$server->getDatabaseConnection()
            );
        });

        $container->registerService('UserHooks', function($c) {
            $userHooks = new UserHooks(
                $c->query('ServerContainer')->getUserSession(),
                $c->query('Request'), 
                $c->query('ItemMapper'),
                $c->query('ServerContainer')->getConfig()
            );

            return $userHooks;
        });
    }

}
