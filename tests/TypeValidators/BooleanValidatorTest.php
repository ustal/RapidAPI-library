<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 18.06.17
 * Time: 17:26
 */

namespace RapidAPI\Tests\TypeValidators;


use PHPUnit\Framework\TestCase;
use RapidAPI\Service\TypeValidators\BooleanValidator;

class BooleanValidatorTest extends TestCase
{
    /** @var BooleanValidator */
    private $validator;

    public function setUp()
    {
        $this->validator = new BooleanValidator();
    }

    public function testParseSimple()
    {
        $paramData = [
            "name" => "testSimple",
            "type" => "Boolean",
            "info" => "Some info",
            "required" => false
        ];
        $vendorName = "testSimple";
        $resultTrue = $this->validator->parse($paramData, true, $vendorName);
        $resultFalse = $this->validator->parse($paramData, false, $vendorName);
        $this->assertEquals(true, $resultTrue);
        $this->assertEquals(false, $resultFalse);
    }

    public function testParseResultInt()
    {
        $paramData = [
            "name" => "testInt",
            "type" => "Boolean",
            "info" => "Some info",
            "required" => false,
            "custom" => [
                "toInt" => true
            ]
        ];
        $vendorName = "testInt";
        $resultTrue = $this->validator->parse($paramData, true, $vendorName);
        $resultFalse = $this->validator->parse($paramData, false, $vendorName);
        $this->assertEquals(1, $resultTrue);
        $this->assertEquals(0, $resultFalse);
    }

    public function testParseString()
    {
        $paramData = [
            "name" => "testString",
            "type" => "Boolean",
            "info" => "Some info",
            "required" => false
        ];
        $vendorName = "testString";
        $resultTrue = $this->validator->parse($paramData, "true", $vendorName);
        $resultFalse = $this->validator->parse($paramData, "false", $vendorName);
        $this->assertEquals(true, $resultTrue);
        $this->assertEquals(false, $resultFalse);
    }

    public function testParseResultString()
    {
        $paramData = [
            "name" => "testResultString",
            "type" => "Boolean",
            "info" => "Some info",
            "required" => false,
            "custom" => [
                "toString" => true
            ]
        ];
        $vendorName = "testResultString";
        $resultTrue = $this->validator->parse($paramData, true, $vendorName);
        $resultFalse = $this->validator->parse($paramData, false, $vendorName);
        $this->assertEquals("true", $resultTrue);
        $this->assertEquals("false", $resultFalse);
    }

    public function testParseStringResultString()
    {
        $paramData = [
            "name" => "testResultString",
            "type" => "Boolean",
            "info" => "Some info",
            "required" => false,
            "custom" => [
                "toString" => true
            ]
        ];
        $vendorName = "testResultString";
        $resultTrue = $this->validator->parse($paramData, "true", $vendorName);
        $resultFalse = $this->validator->parse($paramData, "false", $vendorName);
        $this->assertEquals("true", $resultTrue);
        $this->assertEquals("false", $resultFalse);
    }
}