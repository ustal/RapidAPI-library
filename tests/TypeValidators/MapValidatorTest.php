<?php

/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 18.06.17
 * Time: 17:16
 */

namespace RapidAPI\Tests\TypeValidators;

use PHPUnit\Framework\TestCase;
use RapidAPI\Service\TypeValidators\MapValidator;

class MapValidatorTest extends TestCase
{
    /** @var MapValidator */
    private $validator;

    /** @var string */
    private $value = "50.4524465, 30.447226399999998";

    public function setUp()
    {
        $this->validator = new MapValidator();
    }

    public function testParseSimple()
    {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "Map",
            "info" => "Some info",
            "required" => true
        ];
        $expect = "50.4524465,30.447226399999998";
        $result = $this->validator->parse($paramData, $this->value, $vendorName);
        $this->assertEquals($expect, $result);
    }

    public function testParseDivide() {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "Map",
            "info" => "Some info",
            "required" => true,
            "custom"=> [
                "divide"=> true
            ]
        ];
        $expect = ["50.4524465", "30.447226399999998"];
        $result = $this->validator->parse($paramData, $this->value, $vendorName);
        $this->assertEquals($expect, $result);
    }

    public function testParseDivideToFloat() {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "Map",
            "info" => "Some info",
            "required" => true,
            "custom"=> [
                "divide"=> true
            ]
        ];
        $expect = [50.4524465, 30.447226399999998];
        $result = $this->validator->parse($paramData, $this->value, $vendorName);
        $this->assertEquals($expect, $result);
    }

    public function testParseDivideStringLength() {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "Map",
            "info" => "Some info",
            "required" => true,
            "custom"=> [
                "divide"=> true,
                "length" => 3
            ]
        ];
        $expect = ["50.452", "30.447"];
        $result = $this->validator->parse($paramData, $this->value, $vendorName);
        $this->assertEquals($expect, $result);
    }

    public function testParseDivideToFloatLength() {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "Map",
            "info" => "Some info",
            "required" => true,
            "custom"=> [
                "divide"=> true,
                "toFloat" => true,
                "length" => 3
            ]
        ];
        $expect = [50.452, 30.447];
        $result = $this->validator->parse($paramData, $this->value, $vendorName);
        $this->assertEquals($expect, $result);
    }

    public function testParseStringLength() {
        $vendorName = "testName";
        $paramData = [
            "name" => "testName",
            "type" => "Map",
            "info" => "Some info",
            "required" => true,
            "custom"=> [
                "length" => 3
            ]
        ];
        $expect = "50.452,30.447";
        $result = $this->validator->parse($paramData, $this->value, $vendorName);
        $this->assertEquals($expect, $result);
    }

    public function testParseDivideLatLng() {
        $this->markTestSkipped("Waiting to fix MapValidator");
    }
}
