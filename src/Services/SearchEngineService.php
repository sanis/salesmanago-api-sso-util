<?php

namespace SALESmanago\Services;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\ServerException;
use SALESmanago\Exception\SalesManagoError;


class SearchEngineService
{
    const
        METHOD_GET             = 'GET',
        METHOD_SEARCH_ENGINE   = '/api/search/engine/process';

    public $client; //Guzzle client


    public function __construct()
    {
        $headers = array(
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json;charset=UTF-8'
        );

        $this->client = new GuzzleClient([
            'base_uri' => 'https://search.salesmanago.com',
            'verify'   => false,
            'timeout'  => 45.0,
            'defaults' => [
                'headers' => $headers,
            ]
        ]);

    }

    public function searchEngineProcess($search_query, $sei, $vsi, $smc = null)
    {
        $data = [
            'query' => [
                'sei'       => $sei,
                'vsi'       => $vsi,
                'smclient'  => $smc,
                'text'      => $search_query
            ]];

        $data = self::filterDataArray($data);
        try {
            $response = $this->client->request(self::METHOD_GET, self::METHOD_SEARCH_ENGINE, $data);

            //sending request
            //https://search.salesmanago.com/api/search/engine/process?sei=32&vsi=nwcwrfw29w98lx0w&text=blouse

            //https://search.salesmanago.com/api/search/engine/process?sei=32&vsi=nwcwrfw29w98lx0w&text=blouse
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

    public static function filterDataArray($data)
    {
        $data = array_map(function ($var) {
            return is_array($var) ? self::filterDataArray($var) : $var;
        }, $data);
        $data = array_filter($data, function ($value) {
            return !empty($value) || $value === false;
        });
        return $data;
    }
}