<?php


namespace SALESmanago\Services;


use SALESmanago\Entity\Configuration as Settings;
use SALESmanago\Exception\Exception;

use \GuzzleHttp\Client as GuzzleClient;
use \GuzzleHttp\Exception\ConnectException;
use \GuzzleHttp\Exception\ClientException;
use \GuzzleHttp\Exception\GuzzleException;
use \GuzzleHttp\Exception\ServerException;
use SALESmanago\Helper\DataHelper;
use SALESmanago\Helper\EntityDataHelper;

class RequestService
{
    const METHOD_POST = 'POST';

    /** @var GuzzleClient $client */
    private $client;

    /**
     * @var integer
     */
    private $statusCode;

    public function __construct(Settings $Settings)
    {
        $this->setClient($Settings);
    }

    /**
     * @param Settings $settings
     * @param array $headers
     */
    final public function setClient(
        Settings $settings,
        $headers = array(
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json;charset=UTF-8'
        )
    ) {
        $this->client = new GuzzleClient([
            'base_uri' => $settings->getRequestEndpoint(),
            'verify'   => false,
            'timeout'  => 45.0,
            'defaults' => [
                'headers' => $headers,
            ]
        ]);
    }

    /**
     * @throws Exception
     * @param string $method
     * @param string $uri
     * @param array $data
     * @return array
     */
    final public function request($method, $uri, $data)
    {
        try {
            $response = $this->client->request($method, $uri, array('json' => $data));
            $this->setStatusCode($response->getStatusCode());
            $rawResponse = $response->getBody()->getContents();

            return json_decode($rawResponse, true);
        } catch (ConnectException $e) {
            throw new Exception($e->getMessage());
        } catch (ClientException $e) {
            throw new Exception($e->getMessage());
        } catch (ServerException $e) {
            throw new Exception($e->getMessage());
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @throws Exception
     * @param array $response
     * @param array $statement
     * @return array
     */
    public function validateCustomResponse($response, $statement = array())
    {
        $condition = array(is_array($response), array_key_exists('success', $response), $response['success'] == true);
        $condition = array_merge($condition, $statement);

        if (!in_array(false, $condition)) {
            return $response;
        } else {
            $message = is_array($response['message'])
                ? EntityDataHelper::setStrFromArr($response['message'], ', ')
                : $response['message'];

            $response['message'] = 'RequestService::ValidateCustomResponse - some of conditions failed; SM - ' . $message;
            $response['success'] = false;
            return $response;
        }
    }

    /**
     * @param int $statusCode
     */
    private function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }
}
