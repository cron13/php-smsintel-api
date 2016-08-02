<?php

namespace seregazhuk\SmsIntel\Adapters;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Response;
use seregazhuk\SmsIntel\Contracts\HttpInterface;

class GuzzleHttpAdapter implements HttpInterface {

    /**
     * @var Client
     */
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }
	
    /**
     * @param $uri
     * @param array $params
     * @return array
     */
    public function get($uri, $params = [])
    {
        if(!empty($params)){
            $uri .= '?'. http_build_query($params);
        }
        $response = $this
            ->client
            ->get($uri)
            ->send();

        return $this->parseResponse($response);
    }

    /**
     * @param string $uri
     * @param array $body
     * @return array
     */
    public function post($uri, $body)
    {
        $response = $this
            ->client
            ->post($uri, [], $body)
            ->send();
        return $this->parseResponse($response);
    }

    /**
     * @param string $url
     * @return $this
     */
    public function setBaseUrl($url)
    {
        $this->client->setBaseUrl($url);

        return $this;
    }

    /**
     * Parses XML from response and returns it as an array
     *
     * @param Response $response
     * @return array
     */
    protected function parseResponse(Response $response)
    {
        $xml = simplexml_load_string($response->getBody(true));
        return (array)$xml->children();
    }
}