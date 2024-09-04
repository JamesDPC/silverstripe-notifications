<?php

namespace Symbiote\Notifications\Tests;

use SilverStripe\Dev\TestOnly;
use SilverStripe\Core\Config\Configurable;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\FieldType\DBDatetime;
use SilverStripe\Security\Member;
use Symbiote\Notifications\Model\NotifiedOn;

/**
 * @author  marcus@symbiote.com.au
 * @license http://silverstripe.org/bsd-license/
 */
class NotifyOnThis extends DataObject implements NotifiedOn, TestOnly
{
    use Configurable;

    private static array $db = [
        'Title' => 'Varchar',
        'NotifyBy' => 'Datetime',
        'Status' => 'Varchar',
    ];

    protected $availableKeywords;

    /**
     * Return a list of all available keywords in the format
     * array(
     *    'keyword' => 'A description'
     * )
     */
    public function getAvailableKeywords(): array
    {
        if (!$this->availableKeywords) {
            $objectFields = $this->config()->get('db');

            $objectFields['Created'] = 'Created';
            $objectFields['LastEdited'] = 'LastEdited';

            $this->availableKeywords = [];

            foreach ($objectFields as $key => $value) {
                $this->availableKeywords[$key] = ['short' => $key, 'long' => $key];
            }
        }

        return $this->availableKeywords;
    }

    /**
     * Gets an associative array of data that can be accessed in
     * notification fields and templates
     */
    public function getNotificationTemplateData(): array
    {
        return [];
    }

    /**
     * Gets the list of recipients for a given notification event, based on this object's
     * state.
     * @param string $event The Identifier of the notification being sent
     */
    public function getRecipients($event): array
    {
        $member = \SilverStripe\Security\Member::create();
        $member->Email = 'dummy@nowhere.com';
        $member->FirstName = "First";
        $member->Surname = "Last";

        return [$member];
    }
}
