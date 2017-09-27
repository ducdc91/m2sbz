<?php

namespace Funk\SbzImport\Model\Sbz;

class HttpRequest
{
    protected $url;
    protected $requestBody;
    protected $requestLength;
    protected $responseBody;
    protected $responseInfo;

    public function __construct($url = null, $requestBody = null)
    {
        $this->url = $url;
        $this->requestBody = $requestBody;
        $this->requestLength = 0;
        $this->responseBody = null;
        $this->responseInfo = null;

        if ($this->requestBody !== null) {
            $this->buildPostBody();
        }
    }

    public function flush()
    {
        $this->requestBody = null;
        $this->requestLength = 0;
        $this->responseBody = null;
        $this->responseInfo = null;
    }

    public function execute()
    {
        $ch = curl_init();
        $this->doExecute($ch);
    }

    public function buildPostBody($data = null)
    {
        $data = ($data !== null) ? $data : $this->requestBody;

        $this->requestBody = $data;
    }

    protected function doExecute(&$curlHandle)
    {
        $this->setCurlOpts($curlHandle);
        $this->responseBody = curl_exec($curlHandle);
        $this->responseInfo = curl_getinfo($curlHandle);
    }

    protected function setCurlOpts(&$curlHandle)
    {
        curl_setopt($curlHandle, CURLOPT_POSTFIELDS, $this->requestBody);
        curl_setopt($curlHandle, CURLOPT_POST, 1);
        curl_setopt($curlHandle, CURLOPT_TIMEOUT, 20);
        curl_setopt($curlHandle, CURLOPT_URL, $this->url);
        curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curlHandle, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'Connection: Keep-Alive'));
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }

    public function getResponseInfo()
    {
        return $this->responseInfo;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function setUrl($url)
    {
        $this->url = $url;
    }
}
