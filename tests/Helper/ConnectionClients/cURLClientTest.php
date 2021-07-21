<?php


namespace Tests\Helper\ConnectionClients;


use PHPUnit\Framework\TestCase;
use SALESmanago\Helper\ConnectionClients\cURLClient;
use Faker;

class cURLClientTest extends TestCase
{

    /**
     * Send request body ($data) to external endpoint (*.pipedream.net)
     * -> receive the same body form external host (endpoint)
     * -> Compare body which was send with body which was returned - to confirm that curl method works fine;
     */
    public function testRequestSuccess()
    {
        $cURLClient = new cURLClient();
        $faker = Faker\Factory::create();

        $cURLClient
            ->setTimeOut(1000)
            ->setUrl('https://entk8a4v9cmou60.m.pipedream.net');

        $data = [
            'name' => $faker->name,
            'email' => $faker->email,
            'text' => $faker->text
        ];

        $cURLClient->request($data);

        $this->assertTrue($this->arraysAreSimilar($data, $cURLClient->jsonDecode()));
    }

    /**
     * Determine if two associative arrays are similar
     *
     * Both arrays must have the same indexes with identical values
     * without respect to key ordering
     *
     * @param array $a
     * @param array $b
     * @return bool
     */
    public function arraysAreSimilar($a, $b) {
        if (count(array_diff_assoc($a, $b))) {
            return false;
        }

        foreach($a as $k => $v) {
            if ($v !== $b[$k]) {
                return false;
            }
        }

        return true;
    }
}