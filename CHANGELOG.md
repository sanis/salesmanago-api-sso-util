SALESmanago library for integrations
------------------------------------
Version 3.0.0 29.01.2021
 - change structure;
 - remove unnecessary functionality;
 - add new mechanism for login;
 - add configuration schema version in Entity\Configuration;

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