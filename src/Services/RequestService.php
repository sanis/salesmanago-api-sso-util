<?php


namespace SALESmanago\Services;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\Response;
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
    private $guzzleAdapter;

    public function __construct(Configuration $conf)
    {
        $this->guzzleAdapter = new GuzzleClientAdapter();
        $this->guzzleAdapter->setClient($conf,
        $headers = array(
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json;charset=UTF-8'
            ));
    }

    /**
     * @throws Exception
     * @param string $method
     * @param string $uri
     * @param array $data
     * @return Response
     */
    final public function request($method, $uri, $data)
    {
        try {
            $response = $this->guzzleAdapter->transfer($method, $uri, $data);
            $this->setStatusCode($response->getStatusCode());
            $rawResponse = $response->getBody()->getContents();

            return $this->toResponse(json_decode($rawResponse, true));
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
     * @param Response $Response
     * @param array $conditions - array of booleans;
     * @return Response
     */
    public function validateCustomResponse(Response $Response, $conditions = array())
    {
        $condition = array_merge(array(boolval($Response->isSuccess())), $conditions);

        if (!in_array(false, $condition)) {
            return $Response;
        } else {
            $message = 'RequestService::ValidateCustomResponse - some of conditions failed; SM - ' . $Response->getMessage();
            $Response->setMessage($message);
            $Response->setStatus(false);

            return $Response;
        }
    }

    /**
     * @param int $statusCode
     */
    private function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    /**
     * @throws Exception
     * @param Response $Response
     * @return Response
     */
    public function validateResponse($Response)
    {
        if ($Response->isSuccess()) {
            return $Response;
        } else {
            throw new Exception($Response->getMessage());
        }
    }

    /**
     * @param array $apiResponse
     * @return Response;
     */
    public function toResponse($apiResponse)
    {
        $Response = new Response();

        $Response
            ->setStatus($apiResponse['success'])
            ->setMessage($apiResponse['message']);

        unset($apiResponse['success']);
        unset($apiResponse['message']);

        if (!empty($apiResponse)) {
            array_walk($apiResponse, function ($value, $key) use (&$Response) {
                $Response->setField($key, $value);
            });
        }

        return $Response;
    }
}
