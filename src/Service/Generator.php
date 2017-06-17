<?php
/**
 * Created by PhpStorm.
 * User: ustal
 * Date: 17.06.17
 * Time: 18:13
 */

namespace RapidAPI\Service;


use RapidAPI\Exception\PackageException;

class Generator
{
    /** @var array */
    private $result = [];

    /**
     * Create array with headers and params for Guzzle
     * @param array  $headers
     * @param array  $urlParam
     * @param array  $params
     * @param string $url
     * @param string $method POST, GET, PUT, PATCH, DELETE
     * @param string $type   binary, json, multipart
     * @return array
     */
    public function createGuzzleData($url, $headers, $urlParam, $params, $method, $type)
    {
        $method = mb_strtoupper($method);
        $result = [];
        $result['headers'] = $headers;
        $result['method'] = $method;
        $result['url'] = $url;

        if ($method == 'GET') {
            $this->addAsQuery(array_merge($params, $urlParam));
        } else {
            if (!empty($urlParam)) {
                $this->addAsQuery($params);
            }

            if ($this->hasFormParamHeader($headers)) {
                $this->addAsFormParams($params);
            } else {
                switch ($type) {
                    case 'BINARY':
                        $this->addAsBody($params);
                        break;
                    case 'JSON':
                        $this->addAsJson($params);
                        break;
                    case 'MULTIPART':
                        $this->addAsMultipart($params);
                        break;
                    default:
                        $this->addAsJson($params);
                        break;
                }
            }
        }

        return $this->result;
    }

    private function addAsFormParams($params)
    {
        $this->result['form_params'] = $params;
    }

    private function addAsQuery($params)
    {
        $this->result['query'] = $params;
    }

    private function addAsMultipart($params)
    {
        $this->result['multipart'] = $this->getMultipartData($params);
    }

    private function addAsJson($params)
    {
        $this->result['json'] = $params;
    }

    private function addAsBody($params)
    {
        $this->result['body'] = $this->getBinaryData($params);
    }

    /**
     * Check if data need send as form_params
     * @param array $headers
     * @return bool
     */
    private function hasFormParamHeader($headers)
    {
        foreach ($headers as $headerName => $headerValue) {
            if (mb_strtolower($headerName) == 'content-type' && mb_strtolower($headerValue) == 'application/x-www-form-urlencoded') {
                return true;
            }
        }
        return false;
    }

    /**
     * @param        $data
     * @param        $urlFromBlock
     * @param string $url
     * @return string
     */
    public function createFullUrl(&$data, $urlFromBlock, $url = '')
    {
        $url = $url . $urlFromBlock;
        $res = preg_replace_callback(
            '/{(\w+)}/',
            function ($match) use (&$data) {
                if (!isset($data[$match[1]])) {
                    throw new PackageException('Cant find variables to URL parse: ' . $match[1]);
                }
                $result = $data[$match[1]];
                unset($data[$match[1]]);
                if (is_array($result)) {
                    return str_replace(' ', '', implode(',', $result));
                }
                return $result;
            },
            $url);
        return $res;
    }

    protected function getMultipartData($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[] = [
                "name" => $key,
                "contents" => $value
            ];
        }

        return $result;
    }

    protected function getBinaryData($data)
    {
        return array_pop($data);
    }
}