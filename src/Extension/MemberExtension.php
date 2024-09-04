<?php

namespace Symbiote\Notifications\Extension;

use SilverStripe\ORM\ArrayList;
use SilverStripe\Core\Extension;
use SilverStripe\View\ArrayData;
use SilverStripe\Control\Director;
use SilverStripe\Security\Permission;
use Symbiote\MemberProfiles\Pages\MemberProfilePage;
use Symbiote\Notifications\Model\InternalNotification;

class MemberExtension extends Extension
{
    public function getNotifications(int $limit = 10, int $offset = 0, array $filter = []): ArrayList
    {
        $filter = array_merge(
            $filter,
            ['ToID' => $this->owner->ID]
        );

        $notifications = ArrayList::create();

        foreach (InternalNotification::get()->filter($filter)->limit($limit, $offset) as $intNote) {
            $notification = ArrayData::create($intNote->toMap());
            $notification->setField('FromUsername', $intNote->From()->getNotificationUsername());
            $notifications->push($notification);
        }

        return $notifications;
    }

    public function getNotificationUsername(): string
    {
        if ($this->owner->Username) {
            return $this->owner->Username;
        }

        return $this->owner->getTitle();
    }
}
