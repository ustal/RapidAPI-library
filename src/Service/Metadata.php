<?php
/**
 * Created by PhpStorm.
 * User: rapidapi
 * Date: 14.04.17
 * Time: 16:49
 */

namespace RapidAPI\Service;


use RapidAPI\Exception\PackageException;

class Metadata
{
    /** @var array */
    private $metaDataFull = [];

    /**
     * @param array|string $metadata
     * @throws PackageException
     */
    public function set($metadata)
    {
        if (is_array($metadata)) {
            $this->setMetadata($metadata);
        }
        else {
            $this->setMetadataFromFile($metadata);
        }
    }

    /**
     * @return array
     */
    public function getClearMetadata()
    {
        $result = $this->metaDataFull;
        foreach ($result['blocks'] as &$block) {
            unset($block['custom']);
            foreach ($block['args'] as &$param) {
                unset($param['custom']);
            }
        }

        return $result;
    }

    /**
     * @param $blockName
     * @return array
     * @throws PackageException
     */
    public function getBlockData($blockName)
    {
        foreach ($this->metaDataFull['blocks'] as $block) {
            if ($block['name'] == $blockName) {
                return $block;
            }
        }
        throw new PackageException("Not found description in metadata for current block", PackageException::BLOCK_NOT_EXIST_CODE);
    }

    protected function setMetadataFromFile($file) {
        $metadataStr = file_get_contents($file, 'r');
        if (!$metadataStr) {
            throw new PackageException('Metadata not found');
        }
        $metadata = json_decode($metadataStr, true);
        if (json_last_error()) {
            throw new PackageException(json_last_error_msg() . '. Incorrect Metadata JSON.', PackageException::JSON_VALIDATION_CODE);
        }
        $this->setMetadata($metadata);
    }

    protected function setMetadata($metadata) {
        $this->metaDataFull = $metadata;
    }
}