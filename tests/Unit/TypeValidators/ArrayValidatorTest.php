<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 18.06.17
 * Time: 17:26
 */

namespace RapidAPI\Tests\Unit\TypeValidators;


use PHPUnit\Framework\TestCase;
use RapidAPI\Service\TypeValidators\ArrayValidator;

class ArrayValidatorTest extends TestCase
{
    /** @var ArrayValidator */
    private $validator;

    public function setUp()
    {
        $this->validator = new ArrayValidator();
    }

    public function testParseSimple()
    {
        $paramData = [
            "name" => "testSimple",
            "type" => "Array",
            "info" => "Some info",
            "required" => false,
            "structure" => [
                [
                    "name" => "test",
                    "type" => "String",
                    "info" => "asda",
                    "required" => false
                ]
            ]
        ];
        $vendorName = "testSimple";
        $value = [["test" => "test 1"], ["test" => "test 2"]];
        $result = $this->validator->parse($paramData, $value, $vendorName);
        $this->assertTrue($value == $result);
    }

    public function testParseKeyValue()
    {
        $paramData = [
            "name" => "testSimple",
            "type" => "Array",
            "info" => "Some info",
            "required" => false,
            "structure" => [
                [
                    "name" => "key",
                    "type" => "String",
                    "info" => "asda",
                    "required" => false
                ],
                [
                    "name" => "value",
                    "type" => "String",
                    "info" => "asdad",
                    "required" => false
                ]
            ],
            "custom" => [
                "keyValue" => [
                    "key" => "key",
                    "value" => "value"
                ]
            ]
        ];
        $vendorName = "testSimple";
        $value = [
            ["key" => "key1", "value" => "value1"],
            ["key" => "key2", "value" => "value2"],
        ];
        $expect = [
            "key1" => "value1",
            "key2" => "value2"
        ];
        $result = $this->validator->parse($paramData, $value, $vendorName);
        $this->assertTrue($expect == $result);
    }
}
