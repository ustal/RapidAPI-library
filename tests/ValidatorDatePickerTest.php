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

class ValidatorDatePickerTest extends TestCase
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
    public function testDatePicker($data, $expect)
    {
        $this->validator->setData(["args" => $data], $this->metadata->getBlockData('testDatePicker'));
        $bodyParam = $this->validator->getBodyParams();
        $this->assertTrue($expect == $bodyParam);
    }

    public function dataProviderDatePicker()
    {
        return [
            [
                "data" => [
                    "dateTest" => "2016-01-01"
                ],
                "expect" => [
                    "dateTest" => "2016-01-01",
                ]
            ],
            [
                "data" => [
                    "dateTest" => "2016-01-01 12:13:15"
                ],
                "expect" => [
                    "dateTest" => "2016-01-01"
                ]
            ],
            [
                "data" => [
                    "dateTest" => "1456880523"
                ],
                "expect" => [
                    "dateTest" => "2016-03-02"
                ]
            ]
        ];
    }
}