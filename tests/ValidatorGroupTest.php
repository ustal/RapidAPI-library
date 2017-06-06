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

class ValidatorGroupTest extends TestCase
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
     * @dataProvider dataProviderException
     * @expectedException \RapidAPI\Exception\RequiredFieldException
     * @expectedExceptionMessage Follow group validation rules: accessToken OR (email AND (pass OR token))
     * @expectedExceptionCode \RapidAPI\Exception\RequiredFieldException::GROUP_VALIDATION_FAIL
     */
    public function testException($data)
    {
        $this->validator->setData($data, $this->metadata->getBlockData('testGroup1'));
    }

    /**
     * @dataProvider dataProviderValid
     * @param $data
     * @param $expectBody
     */
    public function testValid($data, $expectBody)
    {
        $this->validator->setData(["args" => $data], $this->metadata->getBlockData('testGroup1'));
        $bodyParam = $this->validator->getBodyParams();
        $this->assertTrue($bodyParam == $expectBody);
    }

    public function dataProviderException()
    {
        return [
            [
                "args" => []
            ],
            [
                "args" => [
                    "token" => "testToken"
                ]
            ],
            [
                "args" => [
                    "pass" => "testPass"
                ]
            ],
            [
                "args" => [
                    "token" => "testToken",
                    "pass" => "testPass"
                ]
            ]
        ];
    }

    public function dataProviderValid()
    {
        return [
            [
                "args" => [
                    "email" => "test@email.com",
                    "pass" => "testPass",
                    "token" => "testToken"
                ],
                "expect" => [
                    "email" => "test@email.com",
                    "pass" => "testPass",
                    "token" => "testToken"
                ]
            ],
            [
                "args" => [
                    "email" => "test@email.com",
                    "token" => "testToken"
                ],
                "expect" => [
                    "email" => "test@email.com",
                    "token" => "testToken"
                ]
            ],
            [
                "args" => [
                    "email" => "test@email.com",
                    "pass" => "testPass"
                ],
                "expect" => [
                    "email" => "test@email.com",
                    "pass" => "testPass"
                ]
            ],
            [
                "args" => [
                    "accessToken" => "testAccessToken"
                ],
                "expect" => [
                    "accessToken" => "testAccessToken"
                ]
            ]
        ];
    }
}