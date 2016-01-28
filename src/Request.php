<?php namespace RandomOrg;
/**
 * Copyright (c) Jason Swint 2016
 */

/**
 * Class Request
 * @package RandomOrg
 */
class Request {
    protected $version;
    protected $object;

    /**
     * Request constructor.
     * @param $version string
     * @param $method string
     * @param $params array
     * @param $id integer
     */
    public function __construct($version, $method, $params, $id)
    {
        $this->version = $version;
        $this->object = [
            'jsonrpc' => $this->version,
            'method'  => $method,
            'params'  => $params,
            'id'      => $id
        ];
    }

    public function toJson()
    {
        return json_encode($this->object);
    }
}

