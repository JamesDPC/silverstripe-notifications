<?php

namespace Symbiote\Notifications\Model;

use SilverStripe\ORM\DataList;

/**
 * NotifiedOn
 * @author  marcus@symbiote.com.au, shea@livesource.co.nz
 * @license http://silverstripe.org/bsd-license/
 */
interface NotifiedOn
{
    /**
     * Return a list of available keywords in the format
     * array('keyword' => 'A description') to help users format notification fields
     */
    public function getAvailableKeywords(): array;

    /**
     * Gets an associative array of data that can be accessed in
     * notification fields and templates
     */
    public function getNotificationTemplateData(): array;

    /**
     * Gets the list of recipients for a given notification event, based on this object's
     * state.
     */
    public function getRecipients(string $event): array;
}
