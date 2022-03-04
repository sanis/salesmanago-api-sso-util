<?php


namespace Tests\Unit\Services;


use Tests\Unit\TestCaseUnit;
use SALESmanago\Services\RequestService;
use Faker;

class RequestServiceTest extends TestCaseUnit
{
//this test is only for dev purpose:
//    public function testRequest()
//    {
//        $faker = Faker\Factory::create();
//
//        $conf = $this->initConf();
//        $conf->setEndpoint(/*some endpoint with time delay*/);
//        $requestService = new RequestService($conf);
//
//        $method = 'POST';
//        $uri = '/' . $faker->word; //doesn't matter
//
//        //doesn't matter simple array:
//        $data = [
//            'name' => $faker->name,
//            'tel' => $faker->phoneNumber
//        ];
//
//        try {
//            $requestService->request($method, $uri, $data);
//        } catch (\Exception $e) {
//            var_dump($e->getMessage());
//            die('to jest die');
//        }
//    }
}