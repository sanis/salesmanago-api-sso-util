<?php


namespace SALESmanago\Services;


use SALESmanago\Entity\Settings;
use SALESmanago\Exception\SalesManagoError;
use SALESmanago\Exception\SalesManagoException;

class RequestService
{
    const METHOD_POST = 'POST';

    /** @var GuzzleClient $client */
    private $client;

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
     * @throws SalesManagoException
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
            $error = $e->getHandlerContext();
            throw SalesManagoError::handleError($e->getMessage(), 0, true, $error['errno']);
        } catch (ClientException $e) {
            $error = $e->getResponse();
            throw SalesManagoError::handleError($e->getMessage(), $error->getStatusCode());
        } catch (ServerException $e) {
            $error = $e->getResponse();
            throw SalesManagoError::handleError($e->getMessage(), $error->getStatusCode());
        } catch (GuzzleException $e) {
            throw SalesManagoError::handleError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * @throws SalesManagoException
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
            throw SalesManagoError::handleError($response['message'], $this->getStatusCode());
        }
    }
}