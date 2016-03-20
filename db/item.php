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

namespace OCA\LoginNotification\Db;

use OCP\AppFramework\Db\Entity;

/**
 * Item entity class
 */
class Item extends Entity
{

    /**
     * User name
     * 
     * @var string
     */
    protected $uid;

    /**
     * Client IP address
     * 
     * @var string
     */
    protected $remoteAddr;

    /**
     * Creation datetime
     * 
     * @var string
     */
    protected $createdAt;

}
