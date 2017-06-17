<?php
/**
 * Created by PhpStorm.
 * User: rapidapi
 * Date: 14.04.17
 * Time: 14:28
 */

namespace RapidAPI\Service;

class Manager
{
    /** @var DataValidator */
    private $dataValidator;

    /** @var Metadata */
    private $metadata;

    /** @var Generator */
    private $generator;

    /** @var array */
    private $currentBlockMetadata = [];

    /** @var array */
    private $dataFromRequest = [];

    /**
     * Manager constructor.
     * @param DataValidator $dataValidator
     * @param Metadata      $metadata
     * @param Sender        $sender
     * @param Generator     $generator
     */
    public function __construct(DataValidator $dataValidator, Metadata $metadata, Sender $sender, Generator $generator)
    {
        $this->dataValidator = $dataValidator;
        $this->metadata = $metadata;
        $this->sender = $sender;
        $this->generator = $generator;
    }

    /**
     * @param string $blockName       Block name from metadata.json
     * @param array  $dataFromRequest Data from Request (use parsers)
     */
    public function setData($blockName, $dataFromRequest)
    {
        $this->dataFromRequest = $dataFromRequest;
        $this->currentBlockMetadata = $this->metadata->getBlockData($blockName);
        $this->dataValidator->setData($dataFromRequest, $blockName);
    }

    /**
     * @return array Data that will be in URL as key=value&key2=value2
     */
    public function getUrlParams(): array
    {
        return $this->dataValidator->getUrlParams();
    }

    /**
     * @return array Data that will be in body (json, binery, multipart)
     */
    public function getBodyParams(): array
    {
        return $this->dataValidator->getBodyParams();
    }

    /**
     * @param string $url       Endpoint URL
     * @param array  $headers   Array of headers
     * @param array  $urlParams Data that will be in ULR
     * @param array  $params    Data that will be in request body
     * @return array
     */
    public function createGuzzleData($url, $headers, $urlParams, $params)
    {
        $method = $this->currentBlockMetadata['method'];
        $type = $this->getRequestType();
        return $this->generator->createGuzzleData($url, $headers, $urlParams, $params, $method, $type);
    }

    /**
     * Remove replaced params from data
     * @param array  $data Parsed data from request (not the same as urlParams. This is params. Used to replace {example} in url from metadata)
     * @param string $url  Hardcode part of url, if needed (if u use part of url in metadata)
     * @return string
     */
    public function createFullUrl(&$data, $url = '')
    {
        return $this->generator->createFullUrl($data, $this->currentBlockMetadata['url'], $url);
    }

    /**
     * @return string
     */
    private function getRequestType()
    {
        if (isset($this->currentBlockMetadata['type'])) {
            $type = mb_strtoupper($this->currentBlockMetadata['type']);
        } else {
            $type = 'JSON';
        }

        return $type;
    }
}