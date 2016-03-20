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

namespace OCA\LoginNotification\Hooks;

/**
 * User hooks
 */
class UserHooks
{

    private $userSession;
    private $request;
    private $itemMapper;
    private $config;

    public function __construct($userSession, $request, $itemMapper, $config)
    {
        $this->userSession = $userSession;
        $this->request = $request;
        $this->itemMapper = $itemMapper;
        $this->config = $config;
    }

    /**
     * Registers event listeners
     */
    public function register()
    {
        $this->userSession->listen(
            '\OC\User',
            'postLogin',
            [ $this, 'afterLoginSucceeded' ]
        );
    }

    /**
     * Event listener to be executed after a user logged in successfully
     * 
     * @param \OC\User\User $user
     */
    public function afterLoginSucceeded(\OC\User\User $user)
    {
        $uid = $user->getUID();
        $remoteAddress = $this->request->getRemoteAddress();

        if (false === $this->itemMapper->itemExists($uid, $remoteAddress)) {
            $this->writeDatabaseItem($uid, $remoteAddress);
            $this->sendNotificationMail($user);
        }
    }

    /**
     * Creates a database record to prevent multiple notification emails
     * addressing the same login pattern from being sent.
     * 
     * @param string $uid
     * @param string$remoteAddress
     */
    protected function writeDatabaseItem($uid, $remoteAddress)
    {
        $item = new \OCA\LoginNotification\Db\Item();
        $item->setUid($uid);
        $item->setRemoteAddr($remoteAddress);
        $this->itemMapper->insert($item);
    }

    /**
     * Gets the senders' email. Can be set on the oc admin panel.
     * 
     * @return string
     */
    protected function getSenderMailAddress()
    {
        return $this->config->getSystemValue("mail_from_address")
                . "@"
                . $this->config->getSystemValue("mail_domain");
    }

    /**
     * Sends the notification email
     * 
     * @param \OC\User\User $user
     * 
     * @todo email template
     * @todo make "from" display name configurable
     */
    protected function sendNotificationMail($user)
    {
        $mailer = \OC::$server->getMailer();
        $message = $mailer->createMessage();

        $fromEmailAddress = $this->getSenderMailAddress();

        $message->setSubject("Someone tried to log into your ownCloud instance");
        $message->setFrom([ $fromEmailAddress => "ownCloud Login Notifier"]);
        $message->setTo([ $user->getEMailAddress() => $user->getDisplayName()]);

        $message->setPlainBody('Someone tried to log in to your ownCloud instance.'
                . '\n\nUsername: ' . $user->getUID() . '\nUser Agent: '
                . $this->request->server['HTTP_USER_AGENT']);

        $mailer->send($message);
    }

}
