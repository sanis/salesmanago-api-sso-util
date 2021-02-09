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
    const METHOD_POST = 'POST';

    /**
     * @var integer
     */
    private $statusCode;
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
     * @param Response $response
     * @param array $statement
     * @return Response
     */
    public function validateCustomResponse($response, $statement = array())
    {
        $condition = array(is_array($response), array_key_exists('success', $response), $response['success'] == true);
        $condition = array_merge($condition, $statement);

        if (!in_array(false, $condition)) {
            return $this->toResponse($response);
        } else {
            $message = is_array($response['message'])
                ? EntityDataHelper::setStrFromArr($response['message'], ', ')
                : $response['message'];

            $response['message'] = 'RequestService::ValidateCustomResponse - some of conditions failed; SM - ' . $message;
            $response['success'] = false;
            return $this->toResponse($response);
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
     * @param array $response
     * @return Response
     */
    public function validateResponse($response)
    {
        if (is_array($response)
            && array_key_exists('success', $response)
            && $response['success'] == true
        ) {
            return $this->toResponse($response);
        } else {
            //next one fix various SM message forms:
            $message = is_array($response['message'])
                ? implode(', ', $response['message'])
                : $response['message'];
            throw new Exception($message);
        }
    }

    /**
     * @param array $apiResponse
     * @return Response;
     */
    public function toResponse(array $apiResponse)
    {
        $Response = new Response();

        $Response
            ->setStatus($apiResponse['success'])
            ->setMessage(is_array($apiResponse['message'])
                ? implode(' ', $apiResponse['message'])
                : $apiResponse['message']);

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
