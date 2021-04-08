<?php


namespace SALESmanago\Services;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\ConfigurationInterface;
use SALESmanago\Entity\Response;
use SALESmanago\Exception\Exception;

use \GuzzleHttp\Exception\ConnectException;
use \GuzzleHttp\Exception\ClientException;
use \GuzzleHttp\Exception\GuzzleException;
use \GuzzleHttp\Exception\ServerException;
use SALESmanago\Exception\ExceptionCodeResolver;
use SALESmanago\Factories\ReportFactory;


class RequestService
{
    private $guzzleAdapter;

    public function __construct(ConfigurationInterface $conf)
    {
        try {
            $this->guzzleAdapter = new GuzzleClientAdapter();
            $this->guzzleAdapter->setClient($conf,
                $headers = array(
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json;charset=UTF-8'
                ));
        } catch (\Exception $e) {
            throw new Exception('Error while setting Guzzle Client Adapter: ' . $e->getMessage(), 401);
        }
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
            ReportFactory::doDebugReport(Configuration::getInstance(), [$method, $uri, $data]);

            $response = $this->guzzleAdapter->transfer($method, $uri, $data);

            ReportFactory::doDebugReport(Configuration::getInstance(), ['response' => $response]);
            $rawResponse = $response->getBody()->getContents();

            return $this->toResponse(json_decode($rawResponse, true));
        } catch (ConnectException $e) {
            $code = ExceptionCodeResolver::codeFromCurlMessage($e->getMessage(), 400);
            throw new Exception($e->getMessage(), $code);
        } catch (ClientException $e) {
            throw new Exception($e->getMessage(), 420);
        } catch (ServerException $e) {
            throw new Exception($e->getMessage(), 430);
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage(), 440);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), 400);
        }
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
            $code = ExceptionCodeResolver::codeFromResponseMessage($Response->getMessage(), 110);
            throw new Exception($Response->getMessage(), $code);
        }
    }

    /**
     * @param Response $Response
     * @param array $conditions - array of booleans;
     * @return Response
     * @throws Exception
     */
    public function validateCustomResponse(Response $Response, $conditions = array())
    {
        $condition = array_merge(array(boolval($Response->isSuccess())), $conditions);

        if (!in_array(false, $condition)) {
            return $Response;
        } else {
            $message = 'RequestService::ValidateCustomResponse - some of conditions failed; SM - ' . $Response->getMessage();
            $code = ExceptionCodeResolver::codeFromResponseMessage($Response->getMessage(), 100);
            $Response->setMessage($message);
            $Response->setStatus(false);
            throw new Exception($message, $code);
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
