<?php

/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 18.06.17
 * Time: 17:16
 */

namespace RapidAPI\Tests\Unit\TypeValidators;

use PHPUnit\Framework\TestCase;
use RapidAPI\Service\TypeValidators\DatePickerValidator;

class DatePickerValidatorTest extends TestCase
{
    /** @var DatePickerValidator */
    private $validator;

    private $value = '2017-10-30 12:13:15';

    private $valueTimeStamp = '1509365595';

    public function setUp()
    {
        $this->validator = new DatePickerValidator();
    }

    public function testParseSimple()
    {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "DatePicker",
            "info" => "Some info",
            "required" => true
        ];
        $expect = '2017-10-30 12:13:15';
        $result = $this->validator->parse($paramData, $this->value, $vendorName);
        $this->assertEquals($expect, $result);
    }

    public function testParseFromTimestamp() {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "DatePicker",
            "info" => "Some info",
            "required" => true,
            "custom" => [
                "dateTime" => [
                    "fromFormat" => ['timestamp'],
                    "toFormat" => "Y-m-d"
                ],
            ],
        ];
        $expect = '2017-10-30';
        $result = $this->validator->parse($paramData, $this->valueTimeStamp, $vendorName);
        $this->assertEquals($expect, $result);
    }

    public function testParseToNanFormat() {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "DatePicker",
            "info" => "Some info",
            "required" => true,
            "custom" => [
                "dateTime" => [
                    "fromFormat" => ['timestamp']
                ],
            ],
        ];
        $expect = '2017-10-30 12:13:15';
        $result = $this->validator->parse($paramData, $this->valueTimeStamp, $vendorName);
        $this->assertEquals($expect, $result);
    }

    public function testParseToTimestamp() {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "DatePicker",
            "info" => "Some info",
            "required" => true,
            "custom" => [
                "dateTime" => [
                    "fromFormat" => ["RAPID"],
                    "toFormat" => "timestamp"
                ],
            ],
        ];
        $result = $this->validator->parse($paramData, $this->value, $vendorName);
        $this->assertEquals($this->valueTimeStamp, $result);
    }

    public function testParseSameFormats() {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "DatePicker",
            "info" => "Some info",
            "required" => true,
            "custom" => [
                "dateTime" => [
                    "fromFormat" => ["RAPID"],
                    "toFormat" => "RAPID"
                ],
            ],
        ];
        $result = $this->validator->parse($paramData, $this->value, $vendorName);
        $this->assertEquals($this->value, $result);
    }

    /**
     * @expectedException \RapidAPI\Exception\PackageException
     * @expectedExceptionCode \RapidAPI\Exception\PackageException::DATETIME_FORMAT_CODE
     */
    public function testParseError() {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "DatePicker",
            "info" => "Some info",
            "required" => true,
            "custom" => [
                "dateTime" => [
                    "fromFormat" => ["ISO8601"],
                    "toFormat" => "RAPID"
                ],
            ],
        ];
        $result = $this->validator->parse($paramData, "false", $vendorName);
        $this->assertEquals($this->value, $result);
    }

    /**
     * @dataProvider formatDataProvider
     * @param $format
     * @param $expect
     */
    public function testParseToFormat($format, $expect)
    {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "DatePicker",
            "info" => "Some info",
            "required" => true,
            "custom" => [
                "dateTime" => [
                    "toFormat" => $format,
                ],
            ],
        ];
        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $this->value);
        $expectDate = $date->format($expect);
        $result = $this->validator->parse($paramData, $this->value, $vendorName);
        $this->assertEquals($expectDate, $result);
    }

    public function formatDataProvider()
    {
        return [
            'Y-m-d' => ['Y-m-d', 'Y-m-d'],
            'ISO8601' => ['ISO8601', \DateTime::ISO8601],
            'ATOM' => ['ATOM', \DateTime::ATOM],
            'COOKIE' => ['COOKIE', \DateTime::COOKIE],
            'RFC822' => ['RFC822', \DateTime::RFC822],
            'RFC850' => ['RFC850', \DateTime::RFC850],
            'RFC1036' => ['RFC1036', \DateTime::RFC1036],
            'RFC1123' => ['RFC1123', \DateTime::RFC1123],
            'RFC2822' => ['RFC2822', \DateTime::RFC2822],
            'RFC3339' => ['RFC3339', \DateTime::RFC3339],
            'RSS' => ['RSS', \DateTime::RSS],
            'W3C' => ['W3C', \DateTime::W3C],
        ];
    }

    /**
     * @expectedException \RapidAPI\Exception\PackageException
     * @expectedExceptionCode \RapidAPI\Exception\PackageException::DATETIME_FORMAT_CODE
     */
    public function testParseException() {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "DatePicker",
            "info" => "Some info",
            "required" => true,
            "custom" => [
                "dateTime" => [
                    "toFormat" => "timestamp"
                ]
            ]
        ];
        $value = '2017-10-30,112:13:15';
        $this->validator->parse($paramData, $value, $vendorName);
    }
}
