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

class ValidatorListTest extends TestCase
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

    /**
     * @dataProvider dataProviderDatePicker
     */
    public function testList($data, $expect)
    {
        $this->validator->setData(["args" => $data], $this->metadata->getBlockData('testList'));
        $bodyParam = $this->validator->getBodyParams();
        $this->assertTrue($expect == $bodyParam);
    }

    public function dataProviderDatePicker()
    {
        return [
            [
                "data" => [
                    "listTest1" => [1,2,3],
                    "listTest2" => [1,2,3],
                    "listTest3" => "1;2;3",
                    "listTest4" => "1;2;3",
                    "listTest5" => "1;2;3"
                ],
                "expect" => [
                    "listTest1" => [1,2,3],
                    "listTest2" => "1,2,3",
                    "listTest3" => "1;2;3",
                    "listTest4" => [1,2,3],
                    "listTest5" => ["1","2","3"]
                ]
            ]
        ];
    }
}