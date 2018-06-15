<?php

namespace SALESmanago\Services;

use SALESmanago\Entity\Settings;


interface ApiMethodInterface
{
    const METHOD_UPSERT = "/api/contact/upsert",
          METHOD_DELETE = "/api/contact/delete",
          METHOD_ADD_NOTE = "/api/contact/addNote",
          METHOD_ADD_EXT_EVENT = "/api/contact/addContactExtEvent",
          METHOD_UPDATE_EXT_EVENT = "/api/contact/updateContactExtEvent",
          METHOD_BATCH_UPSERT = "/api/contact/batchupsert",
          METHOD_BATCH_ADD_EXT_EVENT = "/api/contact/batchAddContactExtEvent",
          METHOD_STATUS = "/api/contact/basic",
          METHOD_STATUS_BY_ID = "/api/contact/basicById";

    public function contactUpsert(Settings $settings, $user, $options = array(), $properties = array());
    public function contactDelete(Settings $settings, $userEmail = '');
    public function contactAddNote(Settings $settings, $user);
    public function contactExtEvent(Settings $settings, $type, $product, $user, $eventId);
    public function exportContacts(Settings $settings, $upsertDetails);
    public function exportContactExtEvents(Settings $settings, $events);
    public function getContactByEmail(Settings $settings, $userEmail);
    public function getContactById(Settings $settings, $contactId);
}