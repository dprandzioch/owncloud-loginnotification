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

use OCP\IDBConnection;
use OCP\AppFramework\Db\Mapper;

/**
 * Mapper class for the Item entity
 */
class ItemMapper extends Mapper
{

    /**
     * Constructor
     * 
     * @param IDBConnection $db
     */
    public function __construct(IDBConnection $db)
    {
        parent::__construct($db, 'loginnotification_items');
    }

    /**
     * Creates a table record
     * 
     * @param \OCP\AppFramework\Db\Entity $entity
     * 
     * @return \OCP\AppFramework\Db\Entity
     */
    public function insert(\OCP\AppFramework\Db\Entity $entity)
    {
        $sql = 'INSERT INTO `*PREFIX*loginnotification_items` '
                . 'SET uid = ?, remote_addr = ?, created_at = NOW()';

        $stmt = $this->execute($sql, [$entity->getUid(), $entity->getRemoteAddr()]);

        $entity->setId((int) $this->db->lastInsertId("*PREFIX*loginnotification_items"));
        $stmt->closeCursor();

        return $entity;
    }

    /**
     * Checks if a item exists, given it's uid and remote address
     * 
     * @param string $uid
     * @param string $remoteAddress
     * 
     * @return bool
     */
    public function itemExists($uid, $remoteAddress)
    {
        $sql = 'SELECT COUNT(*) AS `count` FROM `*PREFIX*loginnotification_items` ' .
                'WHERE `uid` = ? AND `remote_addr` = ? AND `created_at` >= ?';

        $stmt = $this->execute($sql, [$uid, $remoteAddress, $this->getLastValidDate()]);

        $row = $stmt->fetch();
        $stmt->closeCursor();

        return ($row['count'] > 0);
    }

    /**
     * Deletes records older than 24h
     */
    public function deleteOutdatedItems()
    {
        $sql = 'DELETE FROM `*PREFIX*loginnotification_items` WHERE `created_at` < ?';

        $stmt = $this->execute($sql, [$this->getLastValidDate()]);
        $stmt->closeCursor();
    }

    /**
     * Returns a timestamp from 24h ago
     * 
     * @return string
     */
    protected function getLastValidDate()
    {
        $date = new \DateTime();
        $date->sub(new \DateInterval("PT86400S"));

        return $date->format("Y-m-d H:i:s");
    }

}
