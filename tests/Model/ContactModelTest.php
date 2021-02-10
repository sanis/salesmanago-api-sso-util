<?php

namespace Tests\Model;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Contact\Contact;
use SALESmanago\Entity\Contact\Address;
use SALESmanago\Entity\Contact\Options;

use SALESmanago\Exception\Exception;
use SALESmanago\Model\ContactModel;

use PHPUnit\Framework\TestCase;
use Faker;

class ContactModelTest extends TestCase
{

    public $Contact;
    public $Settings;

    /**
     * @dataProvider provideTestGetContactForUnionTransfer
     * @throws Exception
     * @param Contact $Contact
     * @param array $expectedArray
     */
    public function testGetContactForUnionTransferStructure(Contact $Contact, $expectedArray)
    {
        $Model = new ContactModel($Contact, Configuration::getInstance());
        $contactRequestArray = $Model->getContactForUnionTransfer();

        $aReturn = $this->arrayRecursiveDiff($contactRequestArray, $expectedArray);
        $this->assertEmpty($aReturn);
    }

    /**
     * Compare recursive arrays
     * @param $array1
     * @param $array2
     * @return array
     */
    protected function arrayRecursiveDiff($array1, $array2)
    {
        $aReturn = array();
        foreach ($array1 as $mKey => $mValue) {
            if (array_key_exists($mKey, $array2)) {
                if (is_array($mValue)) {
                    $aRecursiveDiff = $this->arrayRecursiveDiff($mValue, $array2[$mKey]);
                    if (count($aRecursiveDiff)) { $aReturn[$mKey] = $aRecursiveDiff; }
                }
            } else {
                $aReturn[$mKey] = $mValue;
            }
        }
        return $aReturn;
    }

    /**
     * DataProvider
     */
    public function provideTestGetContactForUnionTransfer()
    {
        $Settings = new Settings();
        $Contact = new Contact();

        $dummyData = self::prepareDummyDataForContactEntity();
        $contactFromPlatform = $dummyData['contactFromPlatform'];
        $addressFromPlatform = $dummyData['addressFromPlatform'];
        $optionsFromPlatform = $dummyData['optionsFromPlatform'];

        $expectedArrayStructure = self::prepareDummyExpectedContactStructure($dummyData);

        $Contact->set($contactFromPlatform);

        $Address = $Contact->getAddress();
        $Contact->setAddress(
            $Address->set($addressFromPlatform)
        );

        $Options = $Contact->getOptions();
        $Contact->setOptions(
            $Options->set($optionsFromPlatform)
        );

        yield [$Contact, $expectedArrayStructure];
    }

    public static function prepareDummyDataForContactEntity(){
        $faker = Faker\Factory::create();

        $dummyData = [
            'contactFromPlatform' => [],
            'addressFromPlatform' => [],
            'optionsFromPlatform' => []
        ];

        $dummyData['contactFromPlatform'] = [
            'email'        => $faker->email,
            'name'         => $faker->name,
            'fax'          => $faker->phoneNumber,
            'phone'        => $faker->phoneNumber,
            'company'      => $faker->company,
            'state'        => $faker->randomElement(['CUSTOMER', 'PROSPECT', 'PARTNER', 'OTHER', 'UNKNOWN']),
            'externalId'   => $faker->uuid,
            'birthday'     => $faker->date('Y-m-d', 'now')
        ];

        $dummyData['addressFromPlatform'] = [
            'streetAddress' => $faker->streetAddress,
            'zipCode'  => $faker->postcode,
            'city'     => $faker->city,
            'country'  => $faker->country,
            'province' => $faker->state
        ];

        $optIn = $faker->boolean;
        $dummyData['optionsFromPlatform'] = [
            'async'            => $faker->boolean,
            'forceOptIn'       => $optIn,
            'forceOptOut'      => !$optIn,
            'forcePhoneOptIn'  => $optIn,
            'forcePhoneOptOut' => !$optIn,
            'tagScoring'       => $faker->boolean,
            'tags'             => [$faker->sentence(2), $faker->sentence(2), $faker->sentence(2)],
            'removeTags'       => [$faker->sentence(2), $faker->sentence(2), $faker->sentence(2)],
            'newEmail'         => $faker->email,
            'createdOn'        => $faker->unixTime('now'),
            'lang'             => $faker->languageCode,
            /*'isSubscribes' => $faker->boolean*/
        ];

        return $dummyData;
    }

    public static function prepareDummyExpectedContactStructure($dummyData = [])
    {
        $dummyData = empty($dummyData)
            ? self::prepareDummyDataForContactEntity()
            : $dummyData;

        $contactFromPlatform = $dummyData['contactFromPlatform'];
        $addressFromPlatform = $dummyData['addressFromPlatform'];
        $optionsFromPlatform = $dummyData['optionsFromPlatform'];

        return [
            Options::ASYNC   => $optionsFromPlatform['async'],
            Contact::CONTACT => [
                Contact::EMAIL    => $contactFromPlatform['email'],
                Contact::NAME     => $contactFromPlatform['name'],
                Contact::FAX      => $contactFromPlatform['fax'],
                Contact::PHONE    => $contactFromPlatform['phone'],
                Contact::COMPANY  => $contactFromPlatform['company'],
                Contact::STATE    => $contactFromPlatform['state'],
                Contact::EXT_ID   => $contactFromPlatform['externalId'],
                Contact::ADDRESS  => [
                    Address::STREET_AD => $addressFromPlatform['streetAddress'],
                    Address::ZIP_CODE  => $addressFromPlatform['zipCode'],
                    Address::CITY      => $addressFromPlatform['city'],
                    Address::COUNTRY   => $addressFromPlatform['country'],
                ],
            ],
            Options::N_EMAIL      => $optionsFromPlatform['newEmail'],
            Options::F_OPT_IN     => $optionsFromPlatform['forceOptIn'],
            Options::F_OPT_OUT    => $optionsFromPlatform['forceOptOut'],
            Options::F_P_OPT_IN   => $optionsFromPlatform['forcePhoneOptIn'],
            Options::F_P_OPT_OUT  => $optionsFromPlatform['forcePhoneOptOut'],
            Options::CREATED_ON   => $optionsFromPlatform['createdOn'],
            Contact::BIRTHDAY     => $contactFromPlatform['birthday'],
            Address::PROVINCE     => $addressFromPlatform['province'],
            Options::LANG         => $optionsFromPlatform['lang'],
            Options::TAGS         => $optionsFromPlatform['removeTags'],
            Options::R_TAGS       => $optionsFromPlatform['removeTags'],
            Options::TAGS_SCORING => $optionsFromPlatform['tagScoring']
        ];
    }
}
