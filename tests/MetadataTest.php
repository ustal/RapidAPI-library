<?php

/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 30.05.17
 * Time: 16:21
 */

namespace RapidAPI\Tests;

use PHPUnit\Framework\TestCase;
use RapidAPI\Service\Metadata;

class MetadataTest extends TestCase
{
    /** @var Metadata */
    private $metadata;

    public function setUp()
    {
        $metadata = new Metadata();
        $metadata->set(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'metadata.json');
        $this->metadata = $metadata;
    }

    public function testClearData()
    {
        $data = [
            "package" => "TestMetadata",
            "tagline" => "Test metadata",
            "description" => "test description",
            "image" => "some image link",
            "repo" => "git repo",
            "keywords" => [
                "metadata"
            ],
            "accounts" => [
                "domain" => "google.com",
                "credentials" => [
                    "apiKey"
                ]
            ],
            "blocks" => [
                [
                    "name" => "testBlock1",
                    "description" => "This endpoint allows to receive weather information.",
                    "args" => [
                        [
                            "name" => "testCredentials",
                            "type" => "credentials",
                            "info" => "test credentials info",
                            "required" => true
                        ],
                        [
                            "name" => "testString",
                            "type" => "String",
                            "info" => "test string info",
                            "required" => true
                        ],
                        [
                            "name" => "testNumber",
                            "type" => "Number",
                            "info" => "test number info",
                            "required" => true
                        ],
                        [
                            "name" => "testBoolean",
                            "type" => "Boolean",
                            "info" => "test boolean info",
                            "required" => true
                        ],
                        [
                            "name" => "testMap",
                            "type" => "Map",
                            "info" => "test map info",
                            "required" => true
                        ],
                        [
                            "name" => "testSelect",
                            "type" => "Select",
                            "options" => ["value1", "value2"],
                            "info" => "test select info",
                            "required" => true
                        ],
                        [
                            "name" => "testDatePicker",
                            "type" => "DatePicker",
                            "info" => "test datepicker info",
                            "required" => true
                        ],
                        [
                            "name" => "testList",
                            "type" => "List",
                            "info" => "test list info",
                            "required" => true,
                            "structure" => [
                                "name" => "testListId",
                                "type" => "String",
                                "info" => "some child info"
                            ]
                        ],
                        [
                            "name" => "testArray",
                            "type" => "Array",
                            "info" => "test array info",
                            "required" => true,
                            "structure" => [
                                [
                                    "name" => "testArrayId",
                                    "type" => "Number",
                                    "info" => "some child info part 1"
                                ],
                                [
                                    "name" => "testArrayName",
                                    "type" => "String",
                                    "info" => "some child info part 2"
                                ]
                            ]
                        ]
                    ],
                    "callbacks" => [
                        [
                            "name" => "error",
                            "info" => "Error"
                        ],
                        [
                            "name" => "success",
                            "info" => "Success"
                        ]
                    ]
                ],
                [
                    "name" => "testBlock2",
                    "description" => "This endpoint allows to receive weather information.",
                    "args" => [
                        [
                            "name" => "testCredentials",
                            "type" => "credentials",
                            "info" => "test credentials info",
                            "required" => true
                        ],
                        [
                            "name" => "testString",
                            "type" => "String",
                            "info" => "test string info",
                            "required" => true
                        ],
                        [
                            "name" => "complexEmail",
                            "type" => "String",
                            "info" => "Complex test 1",
                            "required" => true
                        ],
                        [
                            "name" => "complexTwitter",
                            "type" => "String",
                            "info" => "Complex test 2",
                            "required" => true
                        ],
                        [
                            "name" => "fileJson",
                            "type" => "File",
                            "info" => "content file to json",
                            "required" => true
                        ],
                        [
                            "name" => "fileBase64",
                            "type" => "File",
                            "info" => "content to base64",
                            "required" => true
                        ],
                        [
                            "name" => "testNumber",
                            "type" => "Number",
                            "info" => "test number info",
                            "required" => true
                        ],
                        [
                            "name" => "testBoolean",
                            "type" => "Boolean",
                            "info" => "test boolean info to int",
                            "required" => true
                        ],
                        [
                            "name" => "testBoolean2",
                            "type" => "Boolean",
                            "info" => "to string",
                            "required" => true
                        ],
                        [
                            "name" => "testMap",
                            "type" => "Map",
                            "info" => "test map info",
                            "required" => true
                        ],
                        [
                            "name" => "testSelect",
                            "type" => "Select",
                            "options" => ["value1", "value2"],
                            "info" => "test select info",
                            "required" => true
                        ],
                        [
                            "name" => "testDatePicker",
                            "type" => "DatePicker",
                            "info" => "test datepicker info",
                            "required" => true
                        ],
                        [
                            "name" => "testList",
                            "type" => "List",
                            "info" => "test list info",
                            "required" => true,
                            "structure" => [
                                "name" => "testListId",
                                "type" => "String",
                                "info" => "some child info"
                            ]
                        ],
                        [
                            "name" => "testArray",
                            "type" => "Array",
                            "info" => "test array info",
                            "required" => true,
                            "structure" => [
                                [
                                    "name" => "testArrayId",
                                    "type" => "Number",
                                    "info" => "some child info part 1"
                                ],
                                [
                                    "name" => "testArrayName",
                                    "type" => "String",
                                    "info" => "some child info part 2"
                                ]
                            ]
                        ]
                    ],
                    "callbacks" => [
                        [
                            "name" => "error",
                            "info" => "Error"
                        ],
                        [
                            "name" => "success",
                            "info" => "Success"
                        ]
                    ]
                ],
                [
                    "name" => "testBlock3",
                    "description" => "",
                    "args" => [
                        [
                            "name" => "draft",
                            "type" => "Boolean",
                            "info" => "url param",
                            "required" => true
                        ]
                    ],
                    "callbacks" => [
                        [
                            "name" => "error",
                            "info" => "Error"
                        ],
                        [
                            "name" => "success",
                            "info" => "Success"
                        ]
                    ]
                ],
                [
                    "name" => "testMethodException",
                    "description" => "",
                    "args" => [
                        [
                            "name" => "draft",
                            "type" => "Boolean",
                            "info" => "url param",
                            "required" => true
                        ]
                    ],
                    "callbacks" => [
                        [
                            "name" => "error",
                            "info" => "Error"
                        ],
                        [
                            "name" => "success",
                            "info" => "Success"
                        ]
                    ]
                ],
                [
                    "name" => "testUrlException",
                    "description" => "",
                    "args" => [
                        [
                            "name" => "draft",
                            "type" => "Boolean",
                            "info" => "url param",
                            "required" => true
                        ]
                    ],
                    "callbacks" => [
                        [
                            "name" => "error",
                            "info" => "Error"
                        ],
                        [
                            "name" => "success",
                            "info" => "Success"
                        ]
                    ]
                ]
            ]
        ];
        $dataFromMetadata = $this->metadata->getClearMetadata();
        $this->assertTrue($data == $dataFromMetadata);
    }

    public function testBlockData()
    {
        $data = [
            "name" => "testBlock1",
            "description" => "This endpoint allows to receive weather information.",
            "custom" => [
                "method" => "POST",
                "url" => "http://localsadad.de"
            ],
            "args" => [
                [
                    "name" => "testCredentials",
                    "type" => "credentials",
                    "info" => "test credentials info",
                    "required" => true
                ],
                [
                    "name" => "testString",
                    "type" => "String",
                    "info" => "test string info",
                    "required" => true
                ],
                [
                    "name" => "testNumber",
                    "type" => "Number",
                    "info" => "test number info",
                    "required" => true
                ],
                [
                    "name" => "testBoolean",
                    "type" => "Boolean",
                    "info" => "test boolean info",
                    "required" => true
                ],
                [
                    "name" => "testMap",
                    "type" => "Map",
                    "info" => "test map info",
                    "required" => true
                ],
                [
                    "name" => "testSelect",
                    "type" => "Select",
                    "options" => ["value1", "value2"],
                    "info" => "test select info",
                    "required" => true
                ],
                [
                    "name" => "testDatePicker",
                    "type" => "DatePicker",
                    "info" => "test datepicker info",
                    "required" => true
                ],
                [
                    "name" => "testList",
                    "type" => "List",
                    "info" => "test list info",
                    "required" => true,
                    "structure" => [
                        "name" => "testListId",
                        "type" => "String",
                        "info" => "some child info"
                    ]
                ],
                [
                    "name" => "testArray",
                    "type" => "Array",
                    "info" => "test array info",
                    "required" => true,
                    "structure" => [
                        [
                            "name" => "testArrayId",
                            "type" => "Number",
                            "info" => "some child info part 1"
                        ],
                        [
                            "name" => "testArrayName",
                            "type" => "String",
                            "info" => "some child info part 2"
                        ]
                    ]
                ]
            ],
            "callbacks" => [
                [
                    "name" => "error",
                    "info" => "Error"
                ],
                [
                    "name" => "success",
                    "info" => "Success"
                ]
            ]
        ];
        $blockData = $this->metadata->getBlockData('testBlock1');
        $this->assertTrue($blockData == $data);
    }
}