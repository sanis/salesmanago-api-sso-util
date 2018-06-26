<?php

namespace SALESmanago\Services;

use SALESmanago\Entity\Settings;


interface UserAccountInterface
{
    const METHOD_CREATE_LIVE_CHAT = "/api/wm/createLiveChat",
          METHOD_CREATE_BASIC_POPUP = "/api/wm/createBasicPopup",
          METHOD_CREATE_WEB_PUSH_CONSENT = "/api/wm/createWebPushConsentForm",
          METHOD_CREATE_WEB_PUSH_NOTIFICATION = "/api/wm/createWebPushNotification",
          METHOD_CREATE_WEB_PUSH_CONSENT_AND_NOTIFICATION = "/api/wm/createWebPushConsentFormAndNotification",

          METHOD_ADD_SUBSCRIBE_PRODUCTS = "/api/appstore/subscribeProducts",
          METHOD_ACCOUNT_ITEMS = "/api/account/items",

          REDIRECT_APP = "/api/authorization/authorize",
          REFRESH_TOKEN = "/api/authorization/refreshToken",

          METHOD_BATCH_UPSERT = "/api/contact/batchupsert",
          METHOD_BATCH_ADD_EXT_EVENT = "/api/contact/batchAddContactExtEvent";

    public function exportContacts(Settings $settings, $upsertDetails);

    public function exportContactExtEvents(Settings $settings, $events);
}