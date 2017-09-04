<?php
namespace App\Service\Client;

use App\Service\Log\Logger;
use App\Service\Log\LogUtils;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;

/**
 * Created by PhpStorm.
 * User: xiaod
 * Date: 2017/8/24
 * Time: 23:21
 */
class NormalClient
{
    private static $instance;
    private $client;

    public function __construct($baseInfo, $config = [])
    {

        if (!isset($config['timeout'])) {
            $config['timeout'] = 2;
        }
        if (isset($baseInfo['baseUri'])) {
            $config['base_uri'] = $baseInfo['baseUri'];
        }
        $this->client = new Client($config);
    }



    private function request($method, $uri, $params = [], $options = [])
    {
        $startTime = intval(microtime(true) * 1000);
        switch ($method) {
            case 'GET' :
                $options['query'] = $params;
                break;
            case 'POST':
                $options['form_params'] = $params;
                break;
            default:
                $options['body'] = $params;
        }
        $requestId = $this->getRequestId();
        if (!isset($options['headers'])) {
            $options['headers'] = [];
        }
        $options['headers']['X-Request-Id'] = $requestId;
        $config = $this->client->getConfig();
        $parseUrl = parse_url($config['base_uri'].$uri);
        $url = $parseUrl['host'].$parseUrl['path'];
        try {
            $response = $this->client->request(strtoupper($method), $uri, $options);
        } catch (ConnectException $e) {
            //  log
            throw $e;
        }
        $statusCode = $response->getStatusCode();
            //  log
        if ($statusCode != 200) {
            LogUtils::getInstance()->logError("get error response code $statusCode for commapi:$uri, param:".json_encode($params));
        }
//        $body = $response->getBody();
//        $content = $body->getContents();
//        $data = json_decode($body, true);
//        return !empty($data)?$data:$content;
        return $response;
    }

    /**
     * @param $uri
     * @param array $params
     * @return bool
     */
    public function get($uri, $params = [], $options = [])
    {

        $data = $this->request('GET', $uri, $params, $options);
        return $data;
    }

    /**
     * @param $uri
     * @param $params
     * @return bool
     */
    public function post($uri, $params = [], $options = [])
    {

        $data = $this->request('POST', $uri, $params, $options);
        return $data;
    }

    public function getRequestId()
    {
        if (!isset($_SERVER['HTTP_X_REQUEST_ID'])) {
            $_SERVER['HTTP_X_REQUEST_ID'] = md5(uniqid(microtime(true)));
        }
        return $_SERVER['HTTP_X_REQUEST_ID'];
    }

    public function getClient() {
        return $this->client;
    }
}