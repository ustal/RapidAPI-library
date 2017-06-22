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

    public function testGetClearMetadata()
    {
        $data = [
            'package' => 'TestMetadata',
            'tagline' => 'Test metadata',
            'description' => 'test description',
            'image' => 'some image link',
            'repo' => 'git repo',
            'keywords' =>
                [
                    'metadata',
                ],
            'accounts' =>
                [
                    'domain' => 'google.com',
                    'credentials' =>
                        [
                            'apiKey',
                        ],
                ],
            'blocks' =>
                [
                    [
                        'name' => 'testBlock1',
                        'description' => 'This endpoint allows to receive weather information.',
                        'args' =>
                            [
                                [
                                    'name' => 'testCredentials',
                                    'type' => 'credentials',
                                    'info' => 'test credentials info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testString',
                                    'type' => 'String',
                                    'info' => 'test string info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testNumber',
                                    'type' => 'Number',
                                    'info' => 'test number info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testBoolean',
                                    'type' => 'Boolean',
                                    'info' => 'test boolean info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testMap',
                                    'type' => 'Map',
                                    'info' => 'test map info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testSelect',
                                    'type' => 'Select',
                                    'options' =>
                                        [
                                            'value1',
                                            'value2',
                                        ],
                                    'info' => 'test select info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testDatePicker',
                                    'type' => 'DatePicker',
                                    'info' => 'test datepicker info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testList',
                                    'type' => 'List',
                                    'info' => 'test list info',
                                    'required' => true,
                                    'structure' =>
                                        [
                                            'name' => 'testListId',
                                            'type' => 'String',
                                            'info' => 'some child info',
                                        ],
                                ],
                                [
                                    'name' => 'testArray',
                                    'type' => 'Array',
                                    'info' => 'test array info',
                                    'required' => true,
                                    'structure' =>
                                        [

                                            [
                                                'name' => 'testArrayId',
                                                'type' => 'Number',
                                                'info' => 'some child info part 1',
                                            ],
                                            [
                                                'name' => 'testArrayName',
                                                'type' => 'String',
                                                'info' => 'some child info part 2',
                                            ],
                                        ],
                                ],
                            ],
                        'callbacks' =>
                            [
                                [
                                    'name' => 'error',
                                    'info' => 'Error',
                                ],
                                [
                                    'name' => 'success',
                                    'info' => 'Success',
                                ],
                            ],
                    ],
                    [
                        'name' => 'testBlock2',
                        'description' => 'This endpoint allows to receive weather information.',
                        'args' =>
                            [
                                [
                                    'name' => 'testCredentials',
                                    'type' => 'credentials',
                                    'info' => 'test credentials info',
                                    'required' => true
                                ],
                                [
                                    'name' => 'testString',
                                    'type' => 'String',
                                    'info' => 'test string info',
                                    'required' => true
                                ],
                                [
                                    'name' => 'complexEmail',
                                    'type' => 'String',
                                    'info' => 'Complex test 1',
                                    'required' => true
                                ],
                                [
                                    'name' => 'complexTwitter',
                                    'type' => 'String',
                                    'info' => 'Complex test 2',
                                    'required' => true
                                ],
                                [
                                    'name' => 'fileJson',
                                    'type' => 'File',
                                    'info' => 'content file to json',
                                    'required' => true
                                ],
                                [
                                    'name' => 'fileBase64',
                                    'type' => 'File',
                                    'info' => 'content to base64',
                                    'required' => true
                                ],
                                [
                                    'name' => 'testNumber',
                                    'type' => 'Number',
                                    'info' => 'test number info',
                                    'required' => true
                                ],
                                [
                                    'name' => 'testBoolean',
                                    'type' => 'Boolean',
                                    'info' => 'test boolean info to int',
                                    'required' => true
                                ],
                                [
                                    'name' => 'testBoolean2',
                                    'type' => 'Boolean',
                                    'info' => 'to string',
                                    'required' => true
                                ],
                                [
                                    'name' => 'testMap',
                                    'type' => 'Map',
                                    'info' => 'test map info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testSelect',
                                    'type' => 'Select',
                                    'options' =>
                                        [
                                            'value1',
                                            'value2',
                                        ],
                                    'info' => 'test select info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testDatePicker',
                                    'type' => 'DatePicker',
                                    'info' => 'test datepicker info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testList',
                                    'type' => 'List',
                                    'info' => 'test list info',
                                    'required' => true,
                                    'structure' =>
                                        [
                                            'name' => 'testListId',
                                            'type' => 'String',
                                            'info' => 'some child info',
                                        ],
                                ],
                                [
                                    'name' => 'testArray',
                                    'type' => 'Array',
                                    'info' => 'test array info',
                                    'required' => true,
                                    'structure' =>
                                        [
                                            [
                                                'name' => 'testArrayId',
                                                'type' => 'Number',
                                                'info' => 'some child info part 1',
                                            ],
                                            [
                                                'name' => 'testArrayName',
                                                'type' => 'String',
                                                'info' => 'some child info part 2',
                                            ],
                                        ],
                                ],
                            ],
                        'callbacks' =>
                            [
                                [
                                    'name' => 'error',
                                    'info' => 'Error',
                                ],
                                [
                                    'name' => 'success',
                                    'info' => 'Success',
                                ],
                            ],
                    ],
                    [
                        'name' => 'testBlock3',
                        'description' => '',
                        'args' =>
                            [
                                [
                                    'name' => 'draft',
                                    'type' => 'Boolean',
                                    'info' => 'url param',
                                    'required' => true
                                ],
                            ],
                        'callbacks' =>
                            [
                                [
                                    'name' => 'error',
                                    'info' => 'Error',
                                ],
                                [
                                    'name' => 'success',
                                    'info' => 'Success',
                                ],
                            ],
                    ],
                    [
                        'name' => 'testMethodException',
                        'description' => '',
                        'args' =>
                            [
                                [
                                    'name' => 'draft',
                                    'type' => 'Boolean',
                                    'info' => 'url param',
                                    'required' => true
                                ],
                            ],
                        'callbacks' =>
                            [
                                [
                                    'name' => 'error',
                                    'info' => 'Error',
                                ],
                                [
                                    'name' => 'success',
                                    'info' => 'Success',
                                ],
                            ],
                    ],
                    [
                        'name' => 'testRequiredFieldException',
                        'description' => '',
                        'args' =>
                            [
                                [
                                    'name' => 'draft',
                                    'type' => 'Boolean',
                                    'info' => 'url param',
                                    'required' => true
                                ],
                            ],
                        'callbacks' =>
                            [
                                [
                                    'name' => 'error',
                                    'info' => 'Error',
                                ],
                                [
                                    'name' => 'success',
                                    'info' => 'Success',
                                ],
                            ],
                    ],
                    [
                        'name' => 'testUrlException',
                        'description' => '',
                        'args' =>
                            [
                                [
                                    'name' => 'draft',
                                    'type' => 'Boolean',
                                    'info' => 'url param',
                                    'required' => true
                                ],
                            ],
                        'callbacks' =>
                            [
                                [
                                    'name' => 'error',
                                    'info' => 'Error',
                                ],
                                [
                                    'name' => 'success',
                                    'info' => 'Success',
                                ],
                            ],
                    ],
                    [
                        "name" => "testDatePickerException",
                        "description" => "",
                        "args" => [
                            [
                                "name" => "dateTest",
                                "type" => "DatePicker",
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
                        'name' => 'testBoolean',
                        'description' => '',
                        'args' =>
                            [
                                [
                                    'name' => 'booleanInUrl',
                                    'type' => 'Boolean',
                                    'info' => 'Required url param',
                                    'required' => true
                                ],
                                [
                                    'name' => 'booleanInUrl2',
                                    'type' => 'Boolean',
                                    'info' => 'Not required param',
                                    'required' => false
                                ],
                                [
                                    'name' => 'boolean',
                                    'type' => 'Boolean',
                                    'info' => 'Bool to int',
                                    'required' => false
                                ],
                                [
                                    'name' => 'boolean2',
                                    'type' => 'Boolean',
                                    'info' => 'Bool to string',
                                    'required' => false
                                ],
                            ],
                        'callbacks' =>
                            [
                                [
                                    'name' => 'error',
                                    'info' => 'Error',
                                ],
                                [
                                    'name' => 'success',
                                    'info' => 'Success',
                                ],
                            ],
                    ],
                    [
                        'name' => 'testGroup1',
                        'description' => '',
                        'args' =>
                            [

                                [
                                    'name' => 'accessToken',
                                    'type' => 'String',
                                    'info' => 'Required url param',
                                    'required' => false,
                                ],

                                [
                                    'name' => 'token',
                                    'type' => 'String',
                                    'info' => '',
                                    'required' => false,
                                ],

                                [
                                    'name' => 'email',
                                    'type' => 'String',
                                    'info' => '',
                                    'required' => false,
                                ],

                                [
                                    'name' => 'pass',
                                    'type' => 'String',
                                    'info' => '',
                                    'required' => false,
                                ],
                            ],
                        'callbacks' =>
                            [

                                [
                                    'name' => 'error',
                                    'info' => 'Error',
                                ],

                                [
                                    'name' => 'success',
                                    'info' => 'Success',
                                ],
                            ],
                    ],
                    [
                        'name' => 'testGroup2',
                        'description' => '',
                        'args' =>
                            [

                                [
                                    'name' => 'accessToken',
                                    'type' => 'Boolean',
                                    'info' => 'Required url param',
                                    'required' => false,
                                ],
                                [
                                    'name' => 'email',
                                    'type' => 'String',
                                    'info' => '',
                                    'required' => false,
                                ],
                                [
                                    'name' => 'token',
                                    'type' => 'Boolean',
                                    'info' => '',
                                    'required' => false,
                                ],

                                [
                                    'name' => 'pass',
                                    'type' => 'String',
                                    'info' => '',
                                    'required' => false,
                                ],
                            ],
                        'callbacks' =>
                            [
                                [
                                    'name' => 'error',
                                    'info' => 'Error',
                                ],
                                [
                                    'name' => 'success',
                                    'info' => 'Success',
                                ],
                            ],
                    ],
                    [
                        'name' => 'testKeyValueArray',
                        'description' => '',
                        'args' =>
                            [

                                [
                                    'name' => 'keyValue',
                                    'type' => 'Array',
                                    'info' => '',
                                    'required' => false,
                                    'structure' =>
                                        [

                                            [
                                                'name' => 'type',
                                                'type' => 'String',
                                                'info' => '',
                                                'required' => true
                                            ],
                                            [
                                                'name' => 'someValue',
                                                'type' => 'String',
                                                'info' => '',
                                                'required' => true
                                            ]
                                        ]
                                ],
                            ],
                        'callbacks' =>
                            [

                                [
                                    'name' => 'error',
                                    'info' => 'Error',
                                ],

                                [
                                    'name' => 'success',
                                    'info' => 'Success',
                                ],
                            ],
                    ],
                    [
                        "name" => "testDatePicker",
                        "description" => "",
                        "args" => [
                            [
                                "name" => "dateTest",
                                "type" => "DatePicker",
                                "info" => "",
                                "required" => false,
                                "structure" => [
                                    [
                                        "name" => "type",
                                        "type" => "String",
                                        "info" => "",
                                        "required" => true
                                    ],
                                    [
                                        "name" => "someValue",
                                        "type" => "String",
                                        "info" => "",
                                        "required" => true
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
                        "name" => "testList",
                        "description" => "",
                        "args" => [
                            [
                                "name" => "listTest1",
                                "type" => "List",
                                "info" => "",
                                "required" => false,
                                "structure" => [
                                    "name" => "ID",
                                    "type" => "String",
                                    "info" => "",
                                    "required" => false
                                ]
                            ],
                            [
                                "name" => "listTest2",
                                "type" => "List",
                                "info" => "",
                                "required" => false,
                                "structure" => [
                                    "name" => "ID",
                                    "type" => "String",
                                    "info" => "",
                                    "required" => false
                                ]
                            ],
                            [
                                "name" => "listTest3",
                                "type" => "List",
                                "info" => "",
                                "required" => false,
                                "structure" => [
                                    "name" => "ID",
                                    "type" => "String",
                                    "info" => "",
                                    "required" => false
                                ]
                            ],
                            [
                                "name" => "listTest4",
                                "type" => "List",
                                "info" => "",
                                "required" => false,
                                "structure" => [
                                    "name" => "ID",
                                    "type" => "Number",
                                    "info" => "",
                                    "required" => false
                                ]
                            ],
                            [
                                "name" => "listTest5",
                                "type" => "List",
                                "info" => "",
                                "required" => false,
                                "structure" => [
                                    "name" => "ID",
                                    "type" => "String",
                                    "info" => "",
                                    "required" => false
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
                        "name" => "testMap",
                        "description" => "",
                        "args" => [
                            [
                                "name" => "mapTest1",
                                "type" => "Map",
                                "info" => "",
                                "required" => false
                            ],
                            [
                                "name" => "mapTest2",
                                "type" => "Map",
                                "info" => "",
                                "required" => false
                            ],
                            [
                                "name" => "mapTest3",
                                "type" => "Map",
                                "info" => "",
                                "required" => false
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
                        "name" => "testUrlGenerator",
                        "description" => "",
                        "args" => [
                            [
                                "name" => "postId",
                                "type" => "Number",
                                "info" => "",
                                "required" => true
                            ],
                            [
                                "name" => "testValue",
                                "type" => "String",
                                "info" => "",
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
                        "name" => "testCreateGuzzleMultipart",
                        "description" => "",
                        "args" => [
                            [
                                "name" => "postId",
                                "type" => "Number",
                                "info" => "",
                                "required" => true
                            ],
                            [
                                "name" => "testValue",
                                "type" => "String",
                                "info" => "",
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
                ],
        ];
        $clearMetadata = $this->metadata->getClearMetadata();
        $this->assertTrue($clearMetadata == $data);
    }

    public function testGetBlockData()
    {
        $data = [
            'name' => 'testKeyValueArray',
            'description' => '',
            "custom" => [
                "method" => "POST",
                "url" => "http://example.com"
            ],
            'args' =>
                [

                    [
                        'name' => 'keyValue',
                        'type' => 'Array',
                        'info' => '',
                        'required' => false,
                        'structure' =>
                            [

                                [
                                    'name' => 'type',
                                    'type' => 'String',
                                    'info' => '',
                                    'required' => true
                                ],
                                [
                                    'name' => 'someValue',
                                    'type' => 'String',
                                    'info' => '',
                                    'required' => true
                                ]
                            ],
                        "custom" => [
                            "keyValue" => [
                                "key" => "type",
                                "value" => "someValue"
                            ]
                        ]
                    ],
                ],
            'callbacks' =>
                [

                    [
                        'name' => 'error',
                        'info' => 'Error',
                    ],

                    [
                        'name' => 'success',
                        'info' => 'Success',
                    ],
                ],
        ];
        $blockData = $this->metadata->getBlockData('testKeyValueArray');
        $this->assertTrue($blockData == $data);
    }

    /**
     * @expectedException \RapidAPI\Exception\PackageException
     * @expectedExceptionMessage Not found description in metadata for current block
     * @expectedExceptionCode \RapidAPI\Exception\PackageException::BLOCK_NOT_EXIST_CODE
     */
    public function testPackageException()
    {
        $this->metadata->getBlockData('NonExist');
    }

    /**
     * @expectedException \RapidAPI\Exception\PackageException
     * @expectedExceptionMessage Metadata not found
     */
    public function testFileNotFoundException()
    {
        $this->metadata->set('/dev/null');
    }

    public function testMetadataFromArray()
    {
        $data = ['package' => 'TestMetadata',
            'tagline' => 'Test metadata',
            'description' => 'test description',
            'image' => 'some image link',
            'repo' => 'git repo',
            'keywords' =>
                [
                    'metadata',
                ],
            'accounts' =>
                [
                    'domain' => 'google.com',
                    'credentials' =>
                        [
                            'apiKey',
                        ],
                ],
            'blocks' =>
                [
                    [
                        'name' => 'testBlock1',
                        'description' => 'This endpoint allows to receive weather information.',
                        'args' =>
                            [
                                [
                                    'name' => 'testCredentials',
                                    'type' => 'credentials',
                                    'info' => 'test credentials info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testString',
                                    'type' => 'String',
                                    'info' => 'test string info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testNumber',
                                    'type' => 'Number',
                                    'info' => 'test number info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testBoolean',
                                    'type' => 'Boolean',
                                    'info' => 'test boolean info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testMap',
                                    'type' => 'Map',
                                    'info' => 'test map info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testSelect',
                                    'type' => 'Select',
                                    'options' =>
                                        [
                                            'value1',
                                            'value2',
                                        ],
                                    'info' => 'test select info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testDatePicker',
                                    'type' => 'DatePicker',
                                    'info' => 'test datepicker info',
                                    'required' => true,
                                ],
                                [
                                    'name' => 'testList',
                                    'type' => 'List',
                                    'info' => 'test list info',
                                    'required' => true,
                                    'structure' =>
                                        [
                                            'name' => 'testListId',
                                            'type' => 'String',
                                            'info' => 'some child info',
                                        ],
                                ],
                                [
                                    'name' => 'testArray',
                                    'type' => 'Array',
                                    'info' => 'test array info',
                                    'required' => true,
                                    'structure' =>
                                        [

                                            [
                                                'name' => 'testArrayId',
                                                'type' => 'Number',
                                                'info' => 'some child info part 1',
                                            ],
                                            [
                                                'name' => 'testArrayName',
                                                'type' => 'String',
                                                'info' => 'some child info part 2',
                                            ],
                                        ],
                                ],
                            ],
                        'callbacks' =>
                            [
                                [
                                    'name' => 'error',
                                    'info' => 'Error',
                                ],
                                [
                                    'name' => 'success',
                                    'info' => 'Success',
                                ],
                            ],
                    ]
                ]
        ];
        $expect = [
            'name' => 'testBlock1',
            'description' => 'This endpoint allows to receive weather information.',
            'args' =>
                [
                    [
                        'name' => 'testCredentials',
                        'type' => 'credentials',
                        'info' => 'test credentials info',
                        'required' => true,
                    ],
                    [
                        'name' => 'testString',
                        'type' => 'String',
                        'info' => 'test string info',
                        'required' => true,
                    ],
                    [
                        'name' => 'testNumber',
                        'type' => 'Number',
                        'info' => 'test number info',
                        'required' => true,
                    ],
                    [
                        'name' => 'testBoolean',
                        'type' => 'Boolean',
                        'info' => 'test boolean info',
                        'required' => true,
                    ],
                    [
                        'name' => 'testMap',
                        'type' => 'Map',
                        'info' => 'test map info',
                        'required' => true,
                    ],
                    [
                        'name' => 'testSelect',
                        'type' => 'Select',
                        'options' =>
                            [
                                'value1',
                                'value2',
                            ],
                        'info' => 'test select info',
                        'required' => true,
                    ],
                    [
                        'name' => 'testDatePicker',
                        'type' => 'DatePicker',
                        'info' => 'test datepicker info',
                        'required' => true,
                    ],
                    [
                        'name' => 'testList',
                        'type' => 'List',
                        'info' => 'test list info',
                        'required' => true,
                        'structure' =>
                            [
                                'name' => 'testListId',
                                'type' => 'String',
                                'info' => 'some child info',
                            ],
                    ],
                    [
                        'name' => 'testArray',
                        'type' => 'Array',
                        'info' => 'test array info',
                        'required' => true,
                        'structure' =>
                            [

                                [
                                    'name' => 'testArrayId',
                                    'type' => 'Number',
                                    'info' => 'some child info part 1',
                                ],
                                [
                                    'name' => 'testArrayName',
                                    'type' => 'String',
                                    'info' => 'some child info part 2',
                                ],
                            ],
                    ],
                ],
            'callbacks' =>
                [
                    [
                        'name' => 'error',
                        'info' => 'Error',
                    ],
                    [
                        'name' => 'success',
                        'info' => 'Success',
                    ],
                ],
        ];
        $this->metadata->set($data);
        $blockData = $this->metadata->getBlockData('testBlock1');
        $this->assertEquals($expect, $blockData);
    }

    /**
     * @expectedException \RapidAPI\Exception\PackageException
     * @expectedExceptionMessage Syntax error. Incorrect Metadata JSON.
     * @expectedExceptionCode \RapidAPI\Exception\PackageException::JSON_VALIDATION_CODE
     */
    public function testNotValidMetadata()
    {
        $this->metadata->set(__DIR__ . DIRECTORY_SEPARATOR . 'data' . DIRECTORY_SEPARATOR . 'metadata-unvalid.json');
    }
}
