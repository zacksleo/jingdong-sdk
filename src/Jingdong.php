<?php

namespace Zacksleo\JingdongSdk;

use Hanson\Foundation\Foundation;
use Pimple\Exception\UnknownIdentifierException;

/**
 * JingdongClass
 *
 * @property \Zacksleo\JingdongSdk\Api   $api
 */
class Jingdong extends Foundation
{
    private $method = 'jingdong';

    protected $providers = [
        ServiceProvider::class,
    ];

    /**
     * API请求
     *
     * @param string|array $method
     * @param array $params
     * @param array $files
     * @return array
     */
    public function request($method, $params = [], $files = [])
    {
        return $this->api->request($method, $params, $files);
    }

    public function __get($id)
    {
        try {
            return parent::__get($id);
        } catch (UnknownIdentifierException $exception) {
            $this->method .= '.'.$id;

            return $this;
        }
    }

    /**
     * 链式设置 appMethod
     *
     * @param string $method
     * @param mix $arguments
     * @return $this
     */
    public function __call($method, $arguments)
    {
        $this->method .= '.'.$method;

        if (isset($arguments)) {
            $response = $this->api->request($this->method, ...$arguments);
            $this->method = 'jingdong';

            return $response;
        }

        return $this;
    }
}
