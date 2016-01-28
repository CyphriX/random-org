<?php namespace RandomOrg;
    /**
     * Copyright (c) Jason Swint 2016
     */

/**
 * JSON RPC Client Object
 * @package RandomOrg
 * @author Jason Swint <jason@adventuresinphp.com>
 */
class Client
{
    /**
     * The URL for invoking the API.
     * @var string
     */
    protected $apiUrl = 'https://api.random.org/json-rpc/1/invoke';

    /**
     * JSON RPC version (2.0 required for Random.org beta API)
     * @var string
     */
    protected $version;

    /**
     * Content type header required by Random.org spec
     * see https://api.random.org/json-rpc/1/introduction
     *
     * @var string
     */
    protected $contentType = 'application/json-rpc';

    /**
     * API Key for Random.org.
     * @var string
     */
    private $apiKey;

    /**
     * Client constructor.
     */
    public function __construct($version = '2.0', $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->version = $version;
    }

    /**
     * Generate a random password of given length.
     * @param int $length
     * @return string
     * @throws RandomOrgException
     */
    public function getPassword($length = 20)
    {
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $chars .= 'abcdefghijklmnopqrstuvwxyz';
        $chars .= '0123456789';
        $chars .= '#$!^*';

        $params = [
            'apiKey' => $this->getApiKey(),
            'n' => 1,
            'length' => $length,
            'characters' => $chars
        ];
        $request = new Request(
            $this->version,
            'generateStrings',
            $params,
            $this->getRandomID()
        );
        return $this->processSingleObjectRequest($request);
    }

    /**
     * Get client API key for Random.org.
     * @return string
     */
    protected function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * Get pseudo-random ID to pass to Random.org with request.
     * @return int
     */
    protected function getRandomID()
    {
        return rand(1, 99999);
    }

    protected function processSingleObjectRequest(Request $request)
    {
        $postData = $request->toJson();
        /** @var \stdClass $response */
        $response = $this->post($postData);
        if ($this->responseHasErrors($response)) {
            throw new RandomOrgException($response->error->message);
        }
        return $response->result->random->data[0];
    }

    /**
     * POST data to API URL, and return response.
     * @param string $data
     * @return string
     * @throws RandomOrgException
     */
    protected function post($data = '')
    {
        // initialize cURL and set options
        $defaultOptions = [
            CURLOPT_POST => 1,
            CURLOPT_URL => $this->apiUrl,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_FRESH_CONNECT => 1,
            CURLOPT_FORBID_REUSE => 1,
            CURLOPT_TIMEOUT => 60,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_SSL_VERIFYHOST => 2,
            CURLOPT_SSL_VERIFYPEER => 0
        ];
        $contentType = $this->contentType;
        $curlHeaders = [
            "Content-type: $contentType"
        ];
        $curl = curl_init();
        curl_setopt_array($curl, $defaultOptions);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $curlHeaders);

        // execute cURL function and retrieve server response
        $response = curl_exec($curl);
        if (empty($response)) {
            throw new RandomOrgException('No response was received from the server');
        }
        return json_decode($response);
    }

    /**
     * @param $response
     * @return bool
     */
    protected function responseHasErrors($response)
    {
        return array_key_exists('error', $response);
    }

    /**
     * Generate a v4 UUID.
     * @return string
     * @throws RandomOrgException
     */
    public function getUUID()
    {
        $params = [
            'apiKey' => $this->getApiKey(),
            'n' => 1
        ];
        $request = new Request(
            $this->version,
            'generateUUIDs',
            $params,
            $this->getRandomID()
        );
        return $this->processSingleObjectRequest($request);
    }
}




