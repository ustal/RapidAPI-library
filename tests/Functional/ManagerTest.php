<?php

/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 22.06.17
 * Time: 10:19
 */

namespace RapidAPI\Tests\Functional;

use PHPUnit\Framework\TestCase;
use RapidAPI\Service\DataValidator;
use RapidAPI\Service\Generator;
use RapidAPI\Service\Manager;
use RapidAPI\Service\Metadata;
use RapidAPI\Service\Sender;
use RapidAPI\Service\TypeValidators\TypeValidator;

class ManagerTest extends TestCase
{
    /** @var Manager */
    private $manager;

    public function setUp()
    {
        $typeValidator = new TypeValidator();
        $dataValidator = new DataValidator($typeValidator);
        $metadata = new Metadata();
        $sender = new Sender();
        $generator = new Generator();
        $this->manager = new Manager($dataValidator, $metadata, $sender, $generator);
    }

    public function test1()
    {
        $data = [
            "args" => [
                "listTest1" => ["1", "2", "3", "4"],
                'listTest2' => [1, 2, 3, 4],
                'listTest3' => "1, 2, 3, 4",
                'listTest4' => "1; 2; 3; 4",
                'listTest5' => "1; 2; 3; 4",
            ],
        ];
        $expect = [
            "listTest1" => ["1", "2", "3", "4"],
            "listTest2" => "1,2,3,4",
            "listTest3" => "1, 2, 3, 4",
            "listTest4" => [1, 2, 3, 4],
            "listTest5" => ["1", "2", "3", "4"],

        ];
        $this->manager->setMetadata(__DIR__.'/../data/metadata.json');
        $this->manager->setBlockName('testList');
        $this->manager->setDataFromRequest($data);
        $this->manager->start();
        $urlParam = $this->manager->getUrlParams();
        $bodyParam = $this->manager->getBodyParams();
        $this->assertEquals($expect, $bodyParam);
        $this->assertEquals([], $urlParam);
    }

    public function testUrl()
    {
        $data = [
            "args" => [
                "postId" => 3,
                "testValue" => "ok",
            ],
        ];
        $expectUrl = 'http://example.com/post/3';
        $this->manager->setMetadata(__DIR__.'/../data/metadata.json');
        $this->manager->setBlockName('testUrlGenerator');
        $this->manager->setDataFromRequest($data);
        $this->manager->start();
        $bodyParams = $this->manager->getBodyParams();
        $url = $this->manager->createFullUrl($bodyParams);
        $this->assertEquals($expectUrl, $url);
    }

    public function testGuzzleCreate()
    {
        $data = [
            "args" => [
                "postId" => 3,
                "testValue" => "ok",
            ],
        ];
        $expect = [
            'headers' => [],
            'method' => 'POST',
            'url' => 'http://example.com/post/3',
            'json' => [
                "testValue" => "ok",
            ],

        ];
        $this->manager->setMetadata(__DIR__.'/../data/metadata.json');
        $this->manager->setBlockName('testUrlGenerator');
        $this->manager->setDataFromRequest($data);
        $this->manager->start();
        $bodyParams = $this->manager->getBodyParams();
        $urlParams = $this->manager->getUrlParams();
        $url = $this->manager->createFullUrl($bodyParams);
        $result = $this->manager->createGuzzleData($url, [], $urlParams, $bodyParams);
        $this->assertEquals($expect, $result);
    }

    public function testGuzzleCreateMultipart()
    {
        $data = [
            "args" => [
                "postId" => 3,
                "testValue" => "ok",
            ],
        ];
        $expect = [
            'headers' => [],
            'method' => 'POST',
            'url' => 'http://example.com/post/3',
            'multipart' => [
                [
                    "name" => "testValue",
                    "contents" => "ok",
                ],
            ],

        ];
        $this->manager->setMetadata(__DIR__.'/../data/metadata.json');
        $this->manager->setBlockName('testCreateGuzzleMultipart');
        $this->manager->setDataFromRequest($data);
        $this->manager->start();
        $bodyParams = $this->manager->getBodyParams();
        $urlParams = $this->manager->getUrlParams();
        $url = $this->manager->createFullUrl($bodyParams);
        $result = $this->manager->createGuzzleData($url, [], $urlParams, $bodyParams);
        $this->assertEquals($expect, $result);
    }

    public function testGetBlockMetadata()
    {
        $expect = [
            "name" => "testCreateGuzzleMultipart",
            "description" => "",
            "custom" => [
                "method" => "POST",
                "url" => "http://example.com/post/{postId}",
                "type" => "multipart",
            ],
            "args" => [
                [
                    "name" => "postId",
                    "type" => "Number",
                    "info" => "",
                    "required" => true,
                ],
                [
                    "name" => "testValue",
                    "type" => "String",
                    "info" => "",
                    "required" => true,
                ],
            ],
            "callbacks" => [
                [
                    "name" => "error",
                    "info" => "Error",
                ],
                [
                    "name" => "success",
                    "info" => "Success",
                ],
            ],
        ];
        $this->manager->setMetadata(__DIR__.'/../data/metadata.json');
        $this->manager->setBlockName('testCreateGuzzleMultipart');
        $result = $this->manager->getBlockMetadata();
        $this->assertEquals($expect, $result);
    }
}
