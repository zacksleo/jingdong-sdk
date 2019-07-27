<?php

namespace Zacksleo\JingdongSdk;

use Hanson\Foundation\Log;
use Hanson\Foundation\AbstractAPI;
use Hanson\Foundation\Exception\HttpException;

class Api extends AbstractAPI
{
    const VERSION = '2.0';

    private $gateway = 'https://api.jd.com/routerjson';

    private $key;
    private $secret;
    private $inDev = false;

    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * 设置开发模式
     *
     * @param bool $inDev
     * @return void
     */
    public function setDeveloperMode($inDev)
    {
        $this->inDev = $inDev;
    }

    /**
     * 获取网关地址
     *
     * @return string
     */
    private function getGateway()
    {
        return $this->gateway;
    }

    /**
     * 发起请求
     *
     * @param string|array $method
     * @param array $params
     * @param array $files
     * @return void
     */
    public function request($method, $params = null, $files = [])
    {
        if (is_array($method)) {
            $appMethod = $this->autoCompleteAppMethod(key($method));
            $bizName = current($method);
        } elseif (is_string($method)) {
            $appMethod = $this->autoCompleteAppMethod($method);
        } else {
            Log::error('不支持的参数格式');
            throw new \InvalidArgumentException('不支持的参数格式');
        }
        $form = [
            'app_key'           => $this->key,
            'v'                 => self::VERSION,
            'method'            => $appMethod,
            'timestamp'         => date('Y-m-d H:i:s'),
            '360buy_param_json' => $params,
            'sign'              => $this->signature($params),
        ];
        try {
            $http = $this->getHttp();
            $response = $http->request('POST', $this->getGateway(), [
                'form_params' => $params,
            ]);
        } catch (\Exception  $e) {
            Log::error($e->getMessage(), $e->getTrace());
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }

        $res = json_decode((string) $response->getBody(), true);


       return $res;
    }

    /**
     * 如果没有填写前缀，自动补全 appMethod
     *
     * @param string $appMethod
     * @return string
     */
    private function autoCompleteAppMethod($appMethod)
    {
        if (strpos($appMethod, 'jingdong.') !== 0) {
            return 'jingdong.'.$appMethod;
        }

        return $appMethod;
    }

    /**
     * 签名
     *
     * @param array $params
     *
     * @return string
     */
    private function signature($params)
    {
		ksort($params);
		$stringToBeSigned = $this->secret;
		foreach ($params as $k => $v)
		{
			if("@" != substr($v, 0, 1))
			{
				$stringToBeSigned .= "$k$v";
			}
		}
		unset($k, $v);
		$stringToBeSigned .= $this->appSecret;
		return strtoupper(md5($stringToBeSigned));
    }
}
