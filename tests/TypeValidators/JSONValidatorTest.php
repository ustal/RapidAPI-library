<?php

/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 18.06.17
 * Time: 17:16
 */

namespace RapidAPI\Tests\TypeValidators;

use PHPUnit\Framework\TestCase;
use RapidAPI\Service\TypeValidators\JSONValidator;

class JSONValidatorTest extends TestCase
{
    /** @var JSONValidator */
    private $validator;

    public function setUp()
    {
        $this->validator = new JSONValidator();
    }

    public function testParseSimple()
    {
        $vendorName = "testName";
        $value = "{\"name\": 123, \"custom\": {\"name\": \"asd\"}}";
        $paramData = [
            "name" => "testName",
            "type" => "JSON",
            "info" => "Some info",
            "required" => true
        ];
        $expect = [
            "name" => 123,
            "custom" => [
                "name" => "asd"
            ]
        ];
        $result = $this->validator->parse($paramData, $value, $vendorName);
        $this->assertEquals($expect, $result);
    }

    /**
     * @expectedException \RapidAPI\Exception\PackageException
     * @expectedExceptionCode \RapidAPI\Exception\PackageException::JSON_VALIDATION_CODE
     * @expectedExceptionMessage Parse error in: testName
     */
    public function testParseError() {
        $vendorName = "testName";
        $value = "{\"name\": 123, \"custom\": {name\": \"asd\"}}";
        $paramData = [
            "name" => "testName",
            "type" => "JSON",
            "info" => "Some info",
            "required" => true
        ];
        $expect = [
            "name" => 123,
            "custom" => [
                "name" => "asd"
            ]
        ];
        $result = $this->validator->parse($paramData, $value, $vendorName);
        $this->assertEquals($expect, $result);
    }

    public function testParseArray() {
        $vendorName = "testName";
        $value = [
            "name" => 123,
            "custom" => [
                "name" => "asd"
            ]
        ];
        $paramData = [
            "name" => "testName",
            "type" => "JSON",
            "info" => "Some info",
            "required" => true
        ];
        $expect = [
            "name" => 123,
            "custom" => [
                "name" => "asd"
            ]
        ];
        $result = $this->validator->parse($paramData, $value, $vendorName);
        $this->assertEquals($expect, $result);
    }
}
