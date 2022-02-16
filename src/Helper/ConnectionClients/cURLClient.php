<?php


namespace SALESmanago\Helper\ConnectionClients;

use PHPUnit\Framework\TestCase;
use SALESmanago\Exception\Exception;

class cURLClient
{
    const
        DEFAULT_TIME_OUT = 1000;
    /**
     * @var string - request type (GET, PUT, POST, DELETE, etc.);
     */
    protected $type = 'POST';

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string|null
     */
    protected $host = null;

    /**
     * @var string
     */
    protected $endpoint = '';

    /**
     * @var int
     */
    protected $timeOut;

    /**
     * @var null - request response;
     */
    protected $response = null;

    /**
     * @var array - array of header => value
     */
    protected $headers = [];

    /**
     * @param string $param - request type (GET, PUT, POST, DELETE, etc.)
     * @return $this
     */
    public function setType($param)
    {
        $this->type = strtoupper($param);
        return $this;
    }

    /**
     * @param $param
     * @return $this
     */
    public function setTimeOut($param)
    {
        $this->timeOut = $param;
        return $this;
    }

    /**
     * @param string $param
     */
    public function setUrl($param)
    {
        $this->url = $param;
        return $this;
    }

    /**
     * @param string $param
     */
    public function setHost($param)
    {
        $this->host = $param;
        return $this;
    }

    /**
     * @param $param
     */
    public function setEndpoint($param){
        $this->endpoint = $param;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param array $param
     */
    public function setHeader($param)
    {
        $this->headers = array_merge($this->headers, $param);
        return $this;
    }

    /**
     *
     */
    private function buildHeaders()
    {
        $preparedHeader = [];
        if (!empty($this->headers)) {
            foreach ($this->headers as $name => $value) {
                $preparedHeader = array_merge($preparedHeader = [$name.': '. $value]);
            }
        }

        return $preparedHeader;
    }

    /**
     * Reset headers
     * @return $this
     */
    public function resetHeaders()
    {
        $this->headers = [];
        return $this;
    }

    /**
     * Decode json response
     * @return array
     */
    public function responseJsonDecode()
    {
        return json_decode($this->response, true);
    }

    /**
     * Send data with curl oneway;
     * @param $data
     * @param bool $toJson
     * @throws Exception
     */
    public function request($data, $toJson = true)
    {
        $data = $toJson ? json_encode($data) : $data;
        $url  = empty($this->host) ? $this->url : $this->host.$this->endpoint;
        $ch   = curl_init($url);

        if (empty($this->headers)) {
            $this->headers = [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ];
        }

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->type);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if (!isset($this->timeOut) || empty($this->timeOut)) {
            curl_setopt($ch, CURLOPT_TIMEOUT_MS, self::DEFAULT_TIME_OUT);
        }
        curl_setopt(
            $ch,
            CURLOPT_HTTPHEADER,
            $this->buildHeaders()
        );
        $this->response = curl_exec($ch);

        $errno = curl_errno($ch);
        $error = curl_error($ch);

        curl_close($ch);

        $this->throwErrorIfExist($error, $errno);
    }

    /**
     * @param $errMessage
     * @param $errNumber
     * @throws Exception
     * @return bool
     */
    private function throwErrorIfExist($errMessage, $errNumber)
    {
        if(!empty($errNumber)){
            $smErrNumber = ($errNumber < 10) ? '40'.$errNumber : '4'.$errNumber;
            $message = 'SALESmanago cURL error [code:' . $smErrNumber . ']: ' . $errMessage;

            throw new Exception($message, intval($smErrNumber));
        }

        return false;
    }
}