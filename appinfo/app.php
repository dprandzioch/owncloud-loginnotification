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

$app = new Application();
$container = $app->getContainer();
$container->query('UserHooks')->register();

\OCP\Backgroundjob::addRegularTask('\OCA\LoginNotification\Cron\Cleanup', 'run');
