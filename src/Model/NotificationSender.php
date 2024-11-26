<?php

namespace Symbiote\Notifications\Model;

use SilverStripe\ORM\DataObject;
use SilverStripe\Security\Member;

/**
 * NotificationSender
 *
 * @author  marcus@symbiote.com.au, shea@livesource.co.nz
 * @license http://silverstripe.org/bsd-license/
 */
interface NotificationSender
{
    /**
     * Send a notification.
     * Automatically determines the list of users to send to based on the notification
     * object and context
     */
    public function sendNotification(SystemNotification $notification, DataObject $context, array $data);

    /**
     * Send a notification to a single user at a time
     *
     * @param SystemNotification            $notification
     * @param \SilverStripe\ORM\DataObject  $context
     * @param object $user
     * @param array                         $data
     */
    public function sendToUser(SystemNotification $notification, DataObject $context, object $user, array $data);
}
