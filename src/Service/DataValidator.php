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
    protected $groupError = [];

    protected $typeValidator;

    /**
     * DataValidator constructor.
     * @param TypeValidator $typeValidator
     */
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
//        $this->checkBlockMetadata();
        $this->checkErrors();
    }


    /**
     * @return array
     */
//    public function getValidData(): array
//    {
//        return $this->parsedValidData;
//    }

    /**
     * @return array
     */
//    public function getBlockMetadata(): array
//    {
//        return $this->blockMetadata;
//    }

    /**
     * @return array
     */
    public function getUrlParams(): array
    {
        return $this->typeValidator->getUrlParams();
    }

    public function getBodyParams(): array
    {
        return $this->typeValidator->getBodyParams();
    }

    /**
     * @return bool
     */
    protected function isMultipart(): bool
    {
        if (!empty($this->blockMetadata['custom']['type']) && mb_strtolower(
                $this->blockMetadata['custom']['type']
            ) == 'multipart'
        ) {
            return true;
        }

        return false;
    }

    /**
     *
     */
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

    /**
     * @param $group
     * @return bool
     */
    protected function checkGroup($group): bool
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

    /**
     * @param $paramName
     * @return bool
     */
    protected function isNotEmptyParamByName($paramName): bool
    {
        $paramData = $this->findInArgs($paramName);

        return $this->checkNotEmptyParam($paramData);
    }

    /**
     * @param $paramName
     * @return array
     */
    protected function findInArgs($paramName): array
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
    }

    protected function checkErrors()
    {
        $this->checkBlockErrors();
        $this->checkRequiredFieldError();
        $this->checkParsedFieldError();
        $this->checkGroupError();
    }

    /**
     * @throws PackageException
     */
    protected function checkBlockErrors()
    {
        if (!isset($this->blockMetadata['custom']['url'])) {
            throw new PackageException("Cant find vendor's endpoint", PackageException::URL_CODE);
        }
        if (!isset($this->blockMetadata['custom']['method'])) {
            throw new PackageException("Cant find method of vendor's endpoint", PackageException::METHOD_CODE);
        }
    }

    /**
     * @throws RequiredFieldException
     */
    protected function checkRequiredFieldError()
    {
        if (!empty($this->requiredFieldError)) {
            throw new RequiredFieldException(implode(',', $this->requiredFieldError));
        }
    }

    /**
     * @throws PackageException
     */
    protected function checkParsedFieldError()
    {
        if (!empty($this->parsedFieldError)) {
            throw new PackageException(
                "Parse error in: ".implode(',', $this->parsedFieldError),
                PackageException::JSON_VALIDATION_CODE
            );
        }
    }

    /**
     * @throws RequiredFieldException
     */
    protected function checkGroupError()
    {
        if (!empty($this->groupError)) {
            $message = "Follow group validation rules: ";
            foreach ($this->groupError as $group) {
                $glue = $group['required'] == "all" ? " AND " : " OR ";
                $message .= $this->createGroupErrorMessage($group, $glue);
            }
            throw new RequiredFieldException($message, RequiredFieldException::GROUP_VALIDATION_FAIL);
        }
    }

    /**
     * @param $group
     * @param $glue
     * @return string
     */
    protected function createGroupErrorMessage($group, $glue): string
    {
        $message = '';
        if (!$this->isMultiDimensionalArray($group['args'])) {
            $message .= implode($glue, $group['args']);
        } else {
            foreach ($group['args'] as $arg) {
                if (!is_array($arg)) {
                    $message .= $arg.$glue;
                } else {
                    $glue = $arg['required'] == "all" ? " AND " : " OR ";
                    $message .= "(".$this->createGroupErrorMessage($arg, $glue).")";
                }
            }
        }

        return $message;
    }

    /**
     * @param $array
     * @return bool
     */
    public function isMultiDimensionalArray($array): bool
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param $paramData
     */
    protected function parseRequiredDataFromRequest($paramData)
    {
        if ($this->checkNotEmptyParam($paramData)) {
            $this->parseSingleDataFromRequest($paramData);
        } else {
            $this->requiredFieldError[] = $paramData['name'];
        }
    }

    /**
     * @param $paramData
     * @return bool
     */
    protected function checkNotEmptyParam($paramData): bool
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

    /**
     * @param $paramData
     */
    protected function parseSingleDataFromRequest($paramData)
    {
        $name = $paramData['name'];
        $vendorName = $this->getParamVendorName($paramData);
        $paramType = mb_strtolower($paramData['type']);
        $value = $this->getValueFromRequestData($name);
        if ($this->checkNotEmptyParam($paramData)) {
//            if (!empty($paramData['custom']['urlParam'])) {
//
//            }
//            else {
            $this->typeValidator->save($paramData, $value, $vendorName, $paramType);
//            }
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

    /**
     * @param array $paramData
     * @return bool
     */
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

    /**
     * @param $paramName
     * @return null
     */
    protected function getValueFromRequestData($paramName)
    {
        if (isset($this->dataFromRequest['args'][$paramName])) {
            return $this->dataFromRequest['args'][$paramName];
        }

        return null;
    }
}
