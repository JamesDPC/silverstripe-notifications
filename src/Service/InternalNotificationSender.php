<?php

namespace Symbiote\Notifications\Service;

use Exception;
use Symbiote\Notifications\Model\NotificationSender;
use Symbiote\Notifications\Model\SystemNotification;
use Symbiote\Notifications\Model\InternalNotification;
use SilverStripe\Security\Member;
use SilverStripe\Core\Config\Config;

/**
 * EmailNotificationSender
 *
 * @author  marcus@symbiote.com.au
 * @license http://silverstripe.org/bsd-license/
 */
class InternalNotificationSender implements NotificationSender
{
    /**
     * Send a notification via email to the selected users
     */
    public function sendNotification(SystemNotification $notification, NotifiedOn $context, array $data)
    {
        $users = $notification->getRecipients($context);
        foreach ($users as $user) {
            $this->sendToUser($notification, $context, $user, $data);
        }
    }

    /**
     * Send a notification directly to a single user
     */
    public function sendToUser(SystemNotification $notification, NotifiedOn $context, Member $user, array $data)
    {
        if (!($user instanceof Member)) {
            // don't send to non-member user object types
            return;
        }

        $subject = $notification->format($notification->Title, $context, $user, $data);

        $content = $notification->NotificationContent();

        if (!Config::inst()->get(SystemNotification::class, 'html_notifications')) {
            $content = strip_tags($content);
        }

        $message = $notification->format(
            $content,
            $context,
            $user,
            $data
        );

        if ($template = $notification->getTemplate()) {
            $templateData = $notification->getTemplateData($context, $user, $data);
            $templateData->setField('Body', $message);
            try {
                $body = $templateData->renderWith($template);
            } catch (Exception) {
                $body = $message;
            }
        } else {
            $body = $message;
        }

        $contextData = array_merge([
            'ClassName' => $context::class,
            'ID' => $context->ID,
            'Link' => $context->hasMethod('Link') ? $context->Link() : ''
        ], $data);

        $notice = InternalNotification::create([
            'Title' => $subject,
            'Message' => $body,
            'ToID'      => $user->ID,
            'FromID'    => Member::currentUserID(),
            'SentOn'    => date('Y-m-d H:i:s'),
            'SourceObjectID' => $context->ID,
            'SourceNotificationID' => $notification->ID,
            'Context' => $contextData
        ]);

        $notice->write();
    }
}
