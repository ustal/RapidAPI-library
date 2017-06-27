<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 18.06.17
 * Time: 17:26
 */

namespace RapidAPI\Tests\Unit\TypeValidators;


use PHPUnit\Framework\TestCase;
use RapidAPI\Service\TypeValidators\ListValidator;

class ListValidatorTest extends TestCase
{
    /** @var ListValidator */
    private $validator;

    public function setUp()
    {
        $this->validator = new ListValidator();
    }

    public function testParseSimple()
    {
        $paramData = [
            "name" => "testSimple",
            "type" => "List",
            "info" => "Some info",
            "required" => false,
            "structure" => [
                "name" => "test",
                "type" => "String",
                "info" => "asda",
                "required" => false
            ]
        ];
        $vendorName = "testSimple";
        $value = [1, 2, 3];
        $result = $this->validator->parse($paramData, $value, $vendorName);
        $this->assertTrue($value == $result);
    }

    public function testParseFromString()
    {
        $paramData = [
            "name" => "testSimple",
            "type" => "Boolean",
            "info" => "Some info",
            "required" => false,
            "structure" => [
                "name" => "test",
                "type" => "Number",
                "info" => "asda",
                "required" => false
            ],
            "custom" => [
                "toArray" => true
            ]
        ];
        $vendorName = "testSimple";
        $value = "1,2,3";
        $result = $this->validator->parse($paramData, $value, $vendorName);
        $this->assertTrue([1, 2, 3] == $result);
    }

    public function testParseToString()
    {
        $paramData = [
            "name" => "testSimple",
            "type" => "List",
            "info" => "Some info",
            "required" => false,
            "structure" => [
                "name" => "test",
                "type" => "Number",
                "info" => "asda",
                "required" => false
            ],
            "custom" => [
                "toString" => true,
                "glue" => ';'
            ]
        ];
        $vendorName = "testSimple";
        $value = [1, 2, 3];
        $result = $this->validator->parse($paramData, $value, $vendorName);
        $this->assertEquals("1;2;3", $result);
    }
}
