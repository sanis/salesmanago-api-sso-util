SALESmanago library for integrations
------------------------------------
Version 3.0.10 xx.xx.xxxx
 - Added ConsentsCollection to Contact entity

Version 3.0.9 22.10.2021
 - Added separated flags for newsletter and mobile consent status

Version 3.0.8 16.09.2021
 - support for custom cookie TTL (smclient)

Version 3.0.7 01.09.2021
 - setters fixes
 - appendTag(s) fixed
 - endpoint can now be updated after constructing Request Service

Version 3.0.6 21.07.2021
 - fixed synchronization with enabled double opt-in;
 - replace guzzlehttp with simple cUrl client;

Version 3.0.5 07.07.2021
 - fix set tags from array parameter in Entity\Contact\Options::setTags

Version 3.0.4 17.05.2021
 - Truncated fields in request to max length supported by API
 - Moved toArray() from abstract to specific classes

Version 3.0.3 07.04.2021
 - Changed export method
 - Add reporting services

Version 3.0.2 09.03.2021
 - Fix cookie setting via TSCT.php
 - Added error code resolver

Version 3.0.1 03.03.2021
 - fix response in ContactController

Version 3.0.0 24.02.2021
 - change structure;
 - remove unnecessary functionality;
 - add new mechanism for a login;
 - add configuration schema version in Entity\Configuration;
 - change Entity\Configuration to implement ConfigurationInterface;

Version 2.6.2 20.01.2021
 - Add Event types const to Entity\Event\Event;

Version 2.6.1 04.01.2021
 - Add adapter class for GuzzleHttp client;
 - Added Properties;
 - Added IgnoreService to ignore Domains set in plugin settings;
 - Static to object due to PHP5 compatibility;
 - Upgrade guzzle adapter;
 - Refactoring adapter class;
 - Add some unit tests for guzzle adapter class;
 - fix isSubscribes, isUnsubscribes flags;
 - fix checkers;
 - Changes is ApiDoubleOptIn;
 - Moved ApiDoubleOptIn.php Entity to right place;
 - Changes is ApiDoubleOptIn;
 - Moved ApiDoubleOptIn.php Entity to right place;
 - Fix param type in Configuration.php;
 - fix event date, fix synchronization;
 - fix request service prints;
 - fix Contact ExternalID in model;
 - fix ContactModel tagScoring;
 - add an editable endpoint to user settings;
 - fix eventDate;
 - Added AppendTag(s);
 - Implemented Cookie expire time;

Version 2.6.0 23.11.2020
 - add SynchronizationService & ContactService;
 - add CHANGELOG;
