<?php


namespace SALESmanago\Services;


use SALESmanago\Entity\Configuration;
use SALESmanago\Entity\ConfigurationInterface;
use SALESmanago\Entity\Response;
use SALESmanago\Exception\Exception;

use SALESmanago\Exception\ExceptionCodeResolver;
use SALESmanago\Factories\ReportFactory;
use SALESmanago\Helper\ConnectionClients\cURLClient;


class RequestService
{
    private $connClient;

    public function __construct(ConfigurationInterface $conf)
    {
        try {
            $this->connClient = new cURLClient();
            $this->connClient
                ->setHost($conf->getEndpoint())
                ->setHeader([
                    'Accept'       => 'application/json',
                    'Content-Type' => 'application/json;charset=UTF-8'
                ]);
        } catch (\Exception $e) {
            throw new Exception('Error while setting Connection Client: ' . $e->getMessage(), 401);
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

            $this->connClient
                ->setType($method)
                ->setEndpoint($uri);

            $this->connClient->request($data);

            $jsonResponse = $this->connClient->responseJsonDecode();

            ReportFactory::doDebugReport(Configuration::getInstance(), ['response' => $jsonResponse]);

            return $this->toResponse($jsonResponse);
        } catch (\Exception $e) {
            throw new Exception($e->getMessage(), $e->getCode());
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

    /**
     * Update Configuration e.g. Endpoint after Request service has been constructed
     */
    public function updateConfiguration()
    {
        $Configuration = Configuration::getInstance();
        $this->connClient
            ->setHost($Configuration->getEndpoint());
    }
}
