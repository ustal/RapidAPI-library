<?php

/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 21.06.17
 * Time: 13:44
 */

namespace RapidAPI\Tests\Unit;

use PHPUnit\Framework\TestCase;
use RapidAPI\Service\Generator;

class GeneratorTest extends TestCase
{
    /** @var Generator */
    private $generator;

    public function setUp()
    {
        $this->generator = new Generator();
    }

    public function testCreateFullUrl()
    {
        $data = [
            'domain' => 'test',
            'ticketId' => 3,
        ];
        $url = "https://{domain}.example.com/ticket/{ticketId}";
        $result = $this->generator->createFullUrl($data, $url);
        $this->assertEquals([], $data);
        $this->assertEquals('https://test.example.com/ticket/3', $result);

        return $result;
    }

    /**
     * @depends testCreateFullUrl
     * @param $url
     */
    public function testGeneratorGet($url)
    {
        $headers = [
            'Content-type' => 'application/json',
        ];
        $urlParam = [
            'save' => true,
        ];
        $param = [];
        $method = "gEt";
        $type = "json";
        $expect = [
            'query' => [
                'save' => true,
            ],
            'headers' => [
                'Content-type' => 'application/json',
            ],
            'method' => 'GET',
            'url' => $url,
        ];
        $result = $this->generator->createGuzzleData($url, $headers, $urlParam, $param, $method, $type);
        $this->assertEquals($expect, $result);
    }

    public function testMultipart()
    {
        $url = 'https://example.com/forum/post';
        $headers = [];
        $urlParam = [
            'draft' => true,
        ];
        $param = [
            'title' => 'some title',
            'content' => 'some content',
        ];
        $method = "pOst";
        $type = "MULTIPART";
        $expect = [
            'query' => [
                'draft' => true,
            ],
            'headers' => [],
            'url' => $url,
            'method' => 'POST',
            'multipart' => [
                [
                    'name' => 'title',
                    'contents' => 'some title',
                ],
                [
                    'name' => 'content',
                    'contents' => 'some content',
                ],
            ],
        ];
        $result = $this->generator->createGuzzleData($url, $headers, $urlParam, $param, $method, $type);
        $this->assertEquals($result, $expect);
    }

    public function testJSON()
    {
        $url = 'https://example.com/forum/post';
        $headers = [
            'Content-type' => 'application/json',
        ];
        $urlParam = [
            'draft' => false,
        ];
        $param = [
            'title' => 'some title',
            'content' => 'some content',
        ];
        $method = "pUt";
        $type = "JSON";
        $expect = [
            'query' => [
                'draft' => false,
            ],
            'headers' => [
                'Content-type' => 'application/json',
            ],
            'url' => $url,
            'method' => 'PUT',
            'json' => [
                'title' => 'some title',
                'content' => 'some content',
            ],
        ];
        $result = $this->generator->createGuzzleData($url, $headers, $urlParam, $param, $method, $type);
        $this->assertEquals($result, $expect);
    }

    public function testBinary() {
        $url = 'https://example.com/forum/post';
        $headers = [];
        $urlParam = [
            'draft' => false,
        ];
        $param = [
            'data' => 'asdasda12312312'
        ];
        $method = "pUt";
        $type = "BINARY";
        $expect = [
            'query' => [
                'draft' => false,
            ],
            'headers' => [ ],
            'url' => $url,
            'method' => 'PUT',
            'body' => 'asdasda12312312'
        ];
        $result = $this->generator->createGuzzleData($url, $headers, $urlParam, $param, $method, $type);
        $this->assertEquals($result, $expect);
    }

    public function testFormParams() {
        $url = 'https://example.com/forum/post';
        $headers = [
            'Content-type' => 'application/x-www-form-urlencoded',
        ];
        $urlParam = [
            'draft' => false,
        ];
        $param = [
            'title' => 'some title',
            'content' => 'some content',
        ];
        $method = "pUt";
        $type = "JSON";
        $expect = [
            'query' => [
                'draft' => false,
            ],
            'headers' => [
                'Content-type' => 'application/x-www-form-urlencoded',
            ],
            'url' => $url,
            'method' => 'PUT',
            'form_params' => [
                'title' => 'some title',
                'content' => 'some content',
            ],
        ];
        $result = $this->generator->createGuzzleData($url, $headers, $urlParam, $param, $method, $type);
        $this->assertEquals($result, $expect);
    }
}
