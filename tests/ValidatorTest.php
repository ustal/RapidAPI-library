<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 30.05.17
 * Time: 16:57
 */

namespace RapidAPI\Tests;

use PHPUnit\Framework\TestCase;
use RapidAPI\Service\DataValidator;
use RapidAPI\Service\Metadata;

/**
 * Class ValidatorTest
 * @afterClass MetadataTest
 * @package    RapidAPI\Tests
 */
class ValidatorTest extends TestCase
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
     * @dataProvider dataProvider
     * @param $blockName
     * @param $data
     * @param $expectBody
     * @param $expectUrl
     */
    public function testBlock($blockName, $data, $expectBody, $expectUrl)
    {
        $this->validator->setData($data, $this->metadata->getBlockData($blockName));
        $bodyParam = $this->validator->getBodyParams();
        $urlParam = $this->validator->getUrlParams();
        $this->assertTrue($bodyParam == $expectBody);
        $this->assertTrue($urlParam == $expectUrl);
    }



    /**
     * @dataProvider dataProviderBoolean
     * @param $blockName
     * @param $data
     * @param $expectBody
     * @param $expectUrl
     */
    public function testBoolean($blockName, $data, $expectBody, $expectUrl) {$this->validator->setData($data, $this->metadata->getBlockData($blockName));
        $bodyParam = $this->validator->getBodyParams();
        $urlParam = $this->validator->getUrlParams();
        $this->assertTrue($bodyParam == $expectBody);
        $this->assertTrue($urlParam == $expectUrl);
    }

    public function dataProviderBoolean() {
        return [
            [
                "blockName" => "testBoolean",
                "request" => [
                    "args" => [
                        "booleanInUrl" => true,
                        "booleanInUrl2" => true,
                        "boolean" => true,
                        "boolean2" => true
                    ]
                ],
                "expectBody" => [
                    "boolean" => 1,
                    "boolean2" => "true"
                ],
                "expectUrl" => [
                    "booleanInUrl" => "true",
                    "booleanInUrl2" => true
                ]
            ],
            [
                "blockName" => "testBoolean",
                "request" => [
                    "args" => [
                        "booleanInUrl" => false,
                        "booleanInUrl2" => false,
                        "boolean" => false,
                        "boolean2" => false
                    ]
                ],
                "expectBody" => [
                    "boolean" => 0,
                    "boolean2" => "false"
                ],
                "expectUrl" => [
                    "booleanInUrl" => "false",
                    "booleanInUrl2" => false
                ]
            ]
        ];
    }

    public function dataProvider()
    {
        return [
            [
                "blockName" => "testBlock1",
                "request" => [
                    "args" => [
                        "testCredentials" => "123",
                        "testString" => "asdad",
                        "testNumber" => 100,
                        "testBoolean" => true,
                        "testMap" => "50.123, 12.12313",
                        "testSelect" => "value1",
                        "testDatePicker" => "2017-01-01 00:00:01",
                        "testList" => [
                            "asd"
                        ],
                        "testArray" => [
                            [
                                "testArrayId" => 1,
                                "testArrayName" => "Name1"
                            ],
                            [
                                "testArrayId" => 2,
                                "testArrayName" => "Name2"
                            ]
                        ]
                    ]
                ],
                "expectBody" => [
                    "testCredentials" => "123",
                    "testString" => "asdad",
                    "testNumber" => 100,
                    "testBoolean" => true,
                    "testMap" => "50.123, 12.12313",
                    "testSelect" => "value1",
                    "testDatePicker" => "2017-01-01 00:00:01",
                    "testList" => [
                        "asd"
                    ],
                    "testArray" => [
                        [
                            "testArrayId" => 1,
                            "testArrayName" => "Name1"
                        ],
                        [
                            "testArrayId" => 2,
                            "testArrayName" => "Name2"
                        ]
                    ]
                ],
                "expectUrl" => [

                ]
            ],
            [
                "blockName" => "testBlock2",
                "request" => [
                    "args" => [
                        "testCredentials" => "123",
                        "testString" => "asdad",
                        "testNumber" => 100,
                        "testBoolean" => true,
                        "testMap" => "50.123, 12.12313",
                        "testSelect" => "value1",
                        "testDatePicker" => "2017-01-01 00:00:01",
                        "testList" => [
                            "asd"
                        ],
                        "testArray" => [
                            [
                                "testArrayId" => 1,
                                "testArrayName" => "Name1"
                            ],
                            [
                                "testArrayId" => 2,
                                "testArrayName" => "Name2"
                            ]
                        ],
                        "complexEmail" => "asd@asd.com",
                        "complexTwitter" => "asdasd69",
                        "fileJson" => "https://www.dropbox.com/s/s9u180k6ffs8cng/computeDiagnosis.json?dl=1",
                        "fileBase64" => "https://www.dropbox.com/s/s9u180k6ffs8cng/computeDiagnosis.json?dl=1",
                        "testBoolean2" => true
                    ]
                ],
                "expectBody" => [
                    "test" => [
                        "VENDOR_NAME" => "123"
                    ],
                    "test_string" => "asdad",
                    "testNumber" => 100,
                    "test_boolean" => 1,
                    "test_map" => "50.123, 12.12313",
                    "test_select" => "value1",
                    "test_date_picker" => "2017-01-01 00:00:01",
                    "test_list" => [
                        "asd"
                    ],
                    "test_array" => [
                        [
                            "testArrayId" => 1,
                            "testArrayName" => "Name1"
                        ],
                        [
                            "testArrayId" => 2,
                            "testArrayName" => "Name2"
                        ]
                    ],
                    "contacts" => [
                        [
                            "vendorKey" => "complex_email",
                            "vendorValue" => "asd@asd.com"
                        ],
                        [
                            "vendorKey" => "complexTwitter",
                            "vendorValue" => "asdasd69"
                        ]
                    ],
                    "file_json" => [
                        "sex" => "male",
                        "age" => "22",
                        "evidence" => [
                            [
                                "id" => "s_13",
                                "choice_id" => "present"
                            ],
                            [
                                "id" => "s_12",
                                "choice_id" => "present"
                            ],
                            [
                                "id" => "s_1782",
                                "choice_id" => "present"
                            ],
                            [
                                "id" => "s_98",
                                "choice_id" => "present"
                            ],
                            [
                                "id" => "s_100",
                                "choice_id" => "present"
                            ]
                        ],
                        "extras" => []
                    ],
                    "file_base64" => "ewogICJzZXgiOiAibWFsZSIsCiAgImFnZSI6ICIyMiIsCiAgImV2aWRlbmNlIjogWwogICAgewogICAgICAiaWQiOiAic18xMyIsCiAgICAgICJjaG9pY2VfaWQiOiAicHJlc2VudCIKICAgIH0sCiAgICB7CiAgICAgICJpZCI6ICJzXzEyIiwKICAgICAgImNob2ljZV9pZCI6ICJwcmVzZW50IgogICAgfSwKICAgIHsKICAgICAgImlkIjogInNfMTc4MiIsCiAgICAgICJjaG9pY2VfaWQiOiAicHJlc2VudCIKICAgIH0sCiAgICB7CiAgICAgICJpZCI6ICJzXzk4IiwKICAgICAgImNob2ljZV9pZCI6ICJwcmVzZW50IgogICAgfSwKICAgIHsKICAgICAgImlkIjogInNfMTAwIiwKICAgICAgImNob2ljZV9pZCI6ICJwcmVzZW50IgogICAgfQogIF0sCiAgImV4dHJhcyI6IHt9Cn0K",
                    "test_boolean2" => "true"
                ],
                "expectUrl" => [

                ]
            ],
            [
                "blockName" => "testBlock3",
                "request" => [
                    "args" => [
                        "draft" => true
                    ]
                ],
                "expectBody" => [
                ],
                "expectUrl" => [
                    "draft" => true
                ]
            ]
        ];
    }
}