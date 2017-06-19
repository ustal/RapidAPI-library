<?php

/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 18.06.17
 * Time: 17:16
 */

namespace RapidAPI\Tests\TypeValidators;

use PHPUnit\Framework\TestCase;
use RapidAPI\Service\TypeValidators\StringValidator;

class StringValidatorTest extends TestCase
{
    /** @var StringValidator */
    private $validator;

    public function setUp()
    {
        $this->validator = new StringValidator();
    }

    public function testParse()
    {
        $vendorName = "testName";
        $value = "test Value";
        $paramData = [
            "name" => "testName",
            "type" => "String",
            "info" => "Some info",
            "required" => true
        ];
        $result = $this->validator->parse($paramData, $value, $vendorName);
        $this->assertEquals('test Value', $result);
    }
}