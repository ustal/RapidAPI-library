<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 05.06.17
 * Time: 10:34
 */

namespace RapidAPI\Tests;

use PHPUnit\Framework\TestCase;
use RapidAPI\Service\DataValidator;
use RapidAPI\Service\Metadata;

/**
 * Class ValidatorExceptionTest
 * @afterClass MetadataTest
 * @package RapidAPI\Tests
 */
class ValidatorExceptionTest extends TestCase
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
     * @expectedException \RapidAPI\Exception\PackageException
     * @expectedExceptionMessage Not found description in metadata for current block
     * @expectedExceptionCode \RapidAPI\Exception\PackageException::BLOCK_NOT_EXIST_CODE
     */
    public function testPackageException()
    {
        $this->validator->setData([], $this->metadata->getBlockData('NonExist'));
    }

    /**
     * @expectedException \RapidAPI\Exception\RequiredFieldException
     * @expectedExceptionMessage draft
     */
    public function testRequiredFieldException()
    {
        $this->validator->setData([], $this->metadata->getBlockData('testRequiredFieldException'));
    }

    /**
     * @expectedException \RapidAPI\Exception\PackageException
     * @expectedExceptionMessage Cant find method of vendor's endpoint
     * @expectedExceptionCode \RapidAPI\Exception\PackageException::METHOD_CODE
     */
    public function testEmptyMethodException()
    {
        $this->validator->setData(["args" => ["draft" => true]], $this->metadata->getBlockData('testMethodException'));
    }

    /**
     * @expectedException \RapidAPI\Exception\PackageException
     * @expectedExceptionMessage Cant find vendor's endpoint
     */
    public function testEmptyUrlException()
    {
        $this->validator->setData(["args" => ["draft" => true]], $this->metadata->getBlockData('testUrlException'));
    }
}