<?php

namespace RapidAPI\Service;

use RapidAPI\Exception\PackageException;
use RapidAPI\Exception\RequiredFieldException;
use RapidAPI\Service\TypeValidators\TypeValidator;

class DataValidator
{
    /** @var array */
    protected $blockMetadata = [];

    /** @var array */
    protected $requiredFieldError = [];

    /** @var array */
    protected $parsedFieldError = [];

    /** @var array */
    protected $dataFromRequest = [];

    /** @var array */
    protected $parsedValidData = [];

    /** @var array */
    protected $urlParams = [];

    /** @var array */
    protected $bodyParams = [];

    /** @var array */
    protected $groupError = [];

    protected $typeValidator;

    public function __construct(TypeValidator $typeValidator)
    {
        $this->typeValidator = $typeValidator;
    }

    /**
     * @param $dataFromRequest
     * @param $blockMetadata
     */
    public function setData($dataFromRequest, $blockMetadata)
    {
        $this->blockMetadata = $blockMetadata;
        $this->dataFromRequest = $dataFromRequest;
        $this->typeValidator->setMultipart($this->isMultipart());
        $this->checkGroupValidation();
        $this->parseData();
        $this->checkBlockMetadata();
    }


    /**
     * @return array
     */
    public function getValidData(): array
    {
        return $this->parsedValidData;
    }

    /**
     * @return array
     */
    public function getBlockMetadata(): array
    {
        return $this->blockMetadata;
    }

    /**
     * @return array
     */
    public function getUrlParams(): array
    {
        return $this->urlParams;
    }

    public function getBodyParams(): array
    {
        return $this->bodyParams;
    }

    protected function isMultipart() {
        if (!empty($this->blockMetadata['custom']['type']) && mb_strtolower($this->blockMetadata['custom']['type']) == 'multipart') {
            return true;
        }
        return false;
    }

    protected function checkGroupValidation()
    {
        if (!empty($this->blockMetadata['custom']['groups'])) {
            foreach ($this->blockMetadata['custom']['groups'] as $group) {
                $result = $this->checkGroup($group);
                if (!$result) {
                    $this->groupError[] = $group;
                }
            }
        }
    }

    protected function checkGroup($group)
    {
        $result = $group['required'] == "all" ? true : false;
        foreach ($group['args'] as $arg) {
            if (is_array($arg)) {
                $result = $this->checkGroup($arg);
            } else {
                $test = $this->isNotEmptyParamByName($arg);
                if ($group['required'] == "all") {
                    $result = $result && $test;
                } else {
                    $result = $result || $test;
                    if ($result == true) {
                        break;
                    }
                }
            }
        }
        return $result;
    }

    protected function isNotEmptyParamByName($paramName)
    {
        $paramData = $this->findInArgs($paramName);
        return $this->checkNotEmptyParam($paramData);
    }

    protected function findInArgs($paramName)
    {
        foreach ($this->blockMetadata['args'] as $arg) {
            if ($arg['name'] == $paramName) {
                return $arg;
            }
        }
        return [];
    }

    protected function parseData()
    {
        foreach ($this->blockMetadata['args'] as $paramData) {
            if ($paramData['required'] == true) {
                $this->parseRequiredDataFromRequest($paramData);
            } else {
                $this->parseSingleDataFromRequest($paramData);
            }
        }
        $this->checkErrors();
    }

    protected function checkErrors()
    {
        if (!empty($this->requiredFieldError)) {
            throw new RequiredFieldException(implode(',', $this->requiredFieldError));
        }
        if (!empty($this->parsedFieldError)) {
            throw new PackageException("Parse error in: " . implode(',', $this->parsedFieldError), PackageException::JSON_VALIDATION_CODE);
        }
        if (!empty($this->groupError)) {
            $message = "Follow group validation rules: ";
            foreach ($this->groupError as $group) {
                $glue = $group['required'] == "all" ? " AND " : " OR ";
                $message .= $this->createGroupErrorMessage($group, $glue);
            }
            throw new RequiredFieldException($message, RequiredFieldException::GROUP_VALIDATION_FAIL);
        }
    }

    protected function createGroupErrorMessage($group, $glue)
    {
        $message = '';
        if (!$this->isMultiDimensionalArray($group['args'])) {
            $message .= implode($glue, $group['args']);
        } else {
            foreach ($group['args'] as $arg) {
                if (!is_array($arg)) {
                    $message .= $arg . $glue;
                } else {
                    $glue = $arg['required'] == "all" ? " AND " : " OR ";
                    $message .= "(" . $this->createGroupErrorMessage($arg, $glue) . ")";
                }
            }
        }
        return $message;
    }

    protected function parseRequiredDataFromRequest($paramData)
    {
        if ($this->checkNotEmptyParam($paramData)) {
            $this->parseSingleDataFromRequest($paramData);
        } else {
            $this->requiredFieldError[] = $paramData['name'];
        }
    }

    protected function checkNotEmptyParam($paramData)
    {
        $name = $paramData['name'];
        $type = mb_strtolower($paramData['type']);
        $value = $this->getValueFromRequestData($name);
        if ($type == 'array' || $type == 'json') {
            if (!empty($value)) {
                return true;
            }
        } else {
            if ($value !== "" && $value !== null) {
                return true;
            }
        }
        return false;
    }

    protected function parseSingleDataFromRequest($paramData)
    {
        $name = $paramData['name'];
        $vendorName = $this->getParamVendorName($paramData);
        $paramType = mb_strtolower($paramData['type']);
        $value = $this->getValueFromRequestData($name);
        // todo fix double checking required params!
        // todo maybe check if value is not null ?
        if ($this->checkNotEmptyParam($paramData)) {
            // todo add new metadata param "nullable" => true (default false) to send "" or "0" param
            $this->typeValidator->save($paramData, $value, $vendorName, $paramType);
        }
    }

    /**
     * Return param Vendor name or change CamelCase to snake_case
     * @param array $paramData
     * @return string
     */
    protected function getParamVendorName(array $paramData): string
    {
        if (!empty($paramData['custom']['vendorName'])) {
            return $paramData['custom']['vendorName'];
        } else {
            if ($this->toSnakeCase($paramData)) {
                return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $paramData['name']));
            } else {
                return $paramData['name'];
            }
        }
    }

    protected function toSnakeCase(array $paramData): bool
    {
        $result = false;
        if (isset($paramData['custom']['snakeCase'])) {
            $result = $paramData['custom']['snakeCase'];
        } else {
            if (isset($this->blockMetadata['custom']['snakeCase'])) {
                $result = $this->blockMetadata['custom']['snakeCase'];
            }
        }

        return $result;
    }

    protected function checkBlockMetadata()
    {
        if (!isset($this->blockMetadata['custom']['url'])) {
            throw new PackageException("Cant find vendor's endpoint", PackageException::URL_CODE);
        }
        if (!isset($this->blockMetadata['custom']['method'])) {
            throw new PackageException("Cant find method of vendor's endpoint", PackageException::METHOD_CODE);
        }
    }

    protected function getValueFromRequestData($paramName)
    {
        if (isset($this->dataFromRequest['args'][$paramName])) {
            return $this->dataFromRequest['args'][$paramName];
        }
        return null;
    }
}