<?php

/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 18.06.17
 * Time: 17:16
 */

namespace RapidAPI\Tests\Unit\TypeValidators;

use PHPUnit\Framework\TestCase;
use RapidAPI\Service\TypeValidators\NumberValidator;

class NumberValidatorTest extends TestCase
{
    /** @var NumberValidator */
    private $validator;

    public function setUp()
    {
        $this->validator = new NumberValidator();
    }

    public function testParse()
    {
        $vendorName = "testName";
        $value = "1";
        $paramData = [
            "name" => "testName",
            "type" => "Number",
            "info" => "Some info",
            "required" => true
        ];
        $result = $this->validator->parse($paramData, $value, $vendorName);
        $this->assertEquals(1, $result);
    }
}
