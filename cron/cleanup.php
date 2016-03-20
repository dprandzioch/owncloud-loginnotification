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

namespace OCA\LoginNotification\Cron;

use \OCA\LoginNotification\AppInfo\Application;

/**
 * Cleanup cronjob. Deletes locks that are older than 24h.
 */
class Cleanup
{

    /**
     * Runs the cronjob
     */
    public static function run()
    {
        $app = new Application();
        $container = $app->getContainer();
        $container->query('ItemMapper')->deleteOutdatedItems();
    }

}
