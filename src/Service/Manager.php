<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
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

    /** @var string */
    private $blockName;

    /** @var array */
    private $currentBlockMetadata = [];

    /** @var array */
    private $dataFromRequest = [];

    /**
     * Manager constructor.
     * @param DataValidator $dataValidator
     * @param Metadata $metadata
     * @param Sender $sender
     * @param Generator $generator
     */
    public function __construct(DataValidator $dataValidator, Metadata $metadata, Sender $sender, Generator $generator)
    {
        $this->dataValidator = $dataValidator;
        $this->metadata = $metadata;
        $this->sender = $sender;
        $this->generator = $generator;
    }

    /**
     * Set metadata file or array
     * @param $metadata
     */
    public function setMetadata($metadata)
    {
        $this->metadata->set($metadata);
    }

    /**
     * Set parsed data from request (use parser for your framework)
     * @param array $dataFromRequest
     */
    public function setDataFromRequest($dataFromRequest)
    {
        $this->dataFromRequest = $dataFromRequest;
    }

    /**
     * Set current endpoint
     * @param $blockName
     */
    public function setBlockName($blockName)
    {
        $this->blockName = $blockName;
        $this->currentBlockMetadata = $this->metadata->getBlockData($blockName);
    }

    /**
     * Start parsing data from request by validation rules
     */
    public function start()
    {
        $this->dataValidator->setData($this->dataFromRequest, $this->currentBlockMetadata);
    }

    /**
     * @return array Data that will be in URL as key=value&key2=value2
     */
    public function getUrlParams(): array
    {
        return $this->dataValidator->getUrlParams();
    }

    /**
     * @return array Data that will be in body (json, binary, multipart)
     */
    public function getBodyParams(): array
    {
        return $this->dataValidator->getBodyParams();
    }

    /**
     * @return array
     */
    public function getBlockMetadata(): array
    {
        return $this->currentBlockMetadata;
    }

    /**
     * @param string $url Endpoint URL
     * @param array $headers Array of headers
     * @param array $urlParams Data that will be in ULR
     * @param array $bodyParams Data that will be in request body
     * @return array
     */
    public function createGuzzleData($url, $headers, $urlParams, $bodyParams)
    {
        $method = $this->currentBlockMetadata['custom']['method'];
        $type = $this->getRequestType();

        return $this->generator->createGuzzleData($url, $headers, $urlParams, $bodyParams, $method, $type);
    }

    /**
     * Remove replaced params from data
     * @param array $data Parsed data from request (not the same as urlParams. This is params. Used to replace {example} in url from metadata)
     * @param string $url Hardcode part of url, if needed (if u use part of url in metadata)
     * @return string
     */
    public function createFullUrl(&$data, $url = '')
    {
        return $this->generator->createFullUrl($data, $this->currentBlockMetadata['custom']['url'], $url);
    }

    /**
     * @return string
     */
    private function getRequestType()
    {
        if (isset($this->currentBlockMetadata['custom']['type'])) {
            $type = mb_strtoupper($this->currentBlockMetadata['custom']['type']);
        } else {
            $type = 'JSON';
        }

        return $type;
    }
}
