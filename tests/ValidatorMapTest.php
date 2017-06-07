<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 05.06.17
 * Time: 19:16
 */

namespace RapidAPI\Tests;


use PHPUnit\Framework\TestCase;
use RapidAPI\Service\DataValidator;
use RapidAPI\Service\Metadata;

class ValidatorGroupTest extends TestCase
{
    /** @var Metadata */
    private $metadata;

    /** @var DataValidator */
    private $validator;

    public function setUp()
    {
        $this->metadata = new Metadata();
        $this->metadata->set(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'metadata.json');
        $this->validator = new DataValidator();
    }

    public function testMap() {
        $data = [
            "args" => [
                "mapTest1" => "123.111, -20.11111",
                "mapTest2" => "123.222, -20.22222",
                "mapTest3" => "123.333, -20.33333"
            ]
        ];
        $expect = [
            "mapTest1" => [
                123.111,
                -20.11111
            ],
            "latitude" => 123.2,
            "longitude" => -20.2,
            "mapTest3" => "123.333,-20.33333"
        ];
        $this->validator->setData($data, $this->metadata->getBlockData('testMap'));
        $bodyParam = $this->validator->getBodyParams();
        $this->assertTrue($expect == $bodyParam);
    }

    public function testListMap() {
        $this->markTestSkipped("in feature");
    }
}