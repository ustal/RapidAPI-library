<?php

namespace RapidAPI\Service;

use RapidAPI\Exception\PackageException;
use RapidAPI\Exception\RequiredFieldException;

class DataValidator
{
    /** @var array */
    private $blockMetadata = [];

    /** @var array */
    private $requiredFieldError = [];

    /** @var array */
    private $parsedFieldError = [];

    /** @var array */
    private $dataFromRequest = [];

    /** @var array */
    private $parsedValidData = [];

    /** @var array */
    private $urlParams = [];

    /** @var array */
    private $bodyParams = [];

    /** @var array */
    private $groupError = [];

    /**
     * @param $dataFromRequest
     * @param $blockMetadata
     */
    public function setData($dataFromRequest, $blockMetadata)
    {
        $this->blockMetadata = $blockMetadata;
        $this->dataFromRequest = $dataFromRequest;
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

    private function checkGroupValidation()
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

    private function checkGroup($group)
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

    private function isNotEmptyParamByName($paramName)
    {
        $paramData = $this->findInArgs($paramName);
        return $this->checkNotEmptyParam($paramData);
    }

    private function findInArgs($paramName)
    {
        foreach ($this->blockMetadata['args'] as $arg) {
            if ($arg['name'] == $paramName) {
                return $arg;
            }
        }
        return [];
    }

    private function parseData()
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

    private function checkErrors()
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

    private function createGroupErrorMessage($group, $glue)
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

    private function isMultiDimensionalArray($array)
    {
        foreach ($array as $value) {
            if (is_array($value)) {
                return true;
            }
        }
        return false;
    }

    private function parseRequiredDataFromRequest($paramData)
    {
        if ($this->checkNotEmptyParam($paramData)) {
            $this->parseSingleDataFromRequest($paramData);
        } else {
            $this->requiredFieldError[] = $paramData['name'];
        }
    }

    private function checkNotEmptyParam($paramData)
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

    private function parseSingleDataFromRequest($paramData)
    {
        $name = $paramData['name'];
        $vendorName = $this->getParamVendorName($paramData);
        $type = mb_strtolower($paramData['type']);
        $value = $this->getValueFromRequestData($name);
        // todo fix double checking required params!
        // todo maybe check if value is not null ?
        if ($this->checkNotEmptyParam($paramData)) {
            // todo add new metadata param "nullable" => true (default false) to send "" or "0" param
            switch ($type) {
                case 'json':
                    $this->setJSONValue($paramData, $value, $vendorName);
                    break;
                case 'array':
                    $this->setArrayValue($paramData, $value, $vendorName);
                    break;
                case 'list':
                    $this->setListValue($paramData, $value, $vendorName);
                    break;
                case 'boolean':
                    $this->setBooleanValue($paramData, $value, $vendorName);
                    break;
                case 'number':
                    $this->setIntValue($paramData, $value, $vendorName);
                    break;
                case 'file':
                    $this->setFileValue($paramData, $value, $vendorName);
                    break;
                case "datepicker":
                    $this->setDateTimeValue($paramData, $value, $vendorName);
                    break;
                case "map":
                    $this->setMapValue($paramData, $value, $vendorName);
                    break;
                default:
                    $this->setSingleValidData($paramData, $value, $vendorName);
                    break;
            }
        }
    }

    private function setSingleValidData($paramData, $value, $vendorName)
    {
        if (!empty($paramData['custom']['urlParam'])) {
            $this->setSingleValidVariable($this->urlParams, $value, $vendorName, $paramData);
        } else {
            $this->setSingleValidVariable($this->bodyParams, $value, $vendorName, $paramData);
        }
    }

    private function setSingleValidVariable(&$data, $value, $vendorName, $paramData)
    {
        if (!empty($paramData['custom']['wrapName'])) {
            $wrapNameList = explode('.', $paramData['custom']['wrapName']);
            $this->addDepthOfNesting($data, $wrapNameList, $value, $vendorName, $paramData);
        } else {
            if (!empty($paramData['custom']['complex'])) {
                $data[$vendorName] = $this->createComplexValue($paramData, $value, $vendorName);
            } else {
                $data[$vendorName] = $value;
            }
        }
    }

    private function addDepthOfNesting(array &$array, &$depthNameList, $value, $vendorName, $paramData)
    {
        $result = [];
        while (!empty($depthNameList)) {
            $deepName = array_shift($depthNameList);
            if (!isset($array[$deepName]) && !empty($depthNameList)) {
                $array[$deepName] = [];
            }
            if (empty($depthNameList)) {
                if (!empty($paramData['custom']['complex'])) {
                    $array[$deepName][] = $this->createComplexValue($paramData, $value, $vendorName);
                } else {
                    $array[$deepName][$vendorName] = $value;
                }
            }
            $result = $this->addDepthOfNesting($array[$deepName], $depthNameList, $value, $vendorName, $paramData);
        }

        return $result;
    }

    private function createComplexValue($paramData, $value, $vendorName)
    {
        return [
            $paramData['custom']['keyName'] => $vendorName,
            $paramData['custom']['valueName'] => $value
        ];
    }

    /**
     * Return param Vendor name or change CamelCase to snake_case
     * @param array $paramData
     * @return string
     */
    private function getParamVendorName(array $paramData): string
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

    private function toSnakeCase(array $paramData): bool
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

    private function setJSONValue($paramData, $value, $vendorName)
    {
        if (!is_array($value)) {
            $normalizeJson = $this->normalizeJson($value);
            $data = json_decode($normalizeJson, true);
            if (json_last_error()) {
                $this->parsedFieldError[] = $paramData['name'];
            } else {
                $this->setSingleValidData($paramData, $data, $vendorName);
            }
        } else {
            $this->setSingleValidData($paramData, $value, $vendorName);
        }
    }

    private function setFileValue($paramData, $value, $vendorName)
    {
        if (!empty($paramData['custom']['jsonParse'])) {
            $content = file_get_contents($value);
            $this->setJSONValue($paramData, $content, $vendorName);
        } else {
            if (isset($this->blockMetadata['custom']['type']) && $this->blockMetadata['custom']['type'] == 'multipart') {
                $content = fopen($value, 'r');
            } else {
                $content = file_get_contents($value);
                if (isset($paramData['custom']['base64encode']) && filter_var($paramData['custom']['base64encode'], FILTER_VALIDATE_BOOLEAN) == true) {
                    $content = base64_encode($content);
                }
            }
            $this->setSingleValidData($paramData, $content, $vendorName);
        }
    }

    private function setArrayValue($paramData, $value, $vendorName)
    {
        if (!empty($paramData['custom']['keyValue']) && !empty($paramData['custom']['keyValue']['key'] && !empty($paramData['custom']['keyValue']['value']))) {
            $newArray = [];
            foreach ($value as $array) {
                $newArray[$array[$paramData['custom']['keyValue']['key']]] = $array[$paramData['custom']['keyValue']['value']];
            }
            $value = $newArray;
        }

        if (mb_strtolower($this->blockMetadata['custom']['method']) == 'get') {
            $this->setSingleValidData($paramData, http_build_query($value), $vendorName);
        } else {
            $this->setSingleValidData($paramData, $value, $vendorName);
        }
    }

    /**
     * @param array        $paramData
     * @param array|string $value
     * @param string       $vendorName
     */
    private function setListValue($paramData, $value, $vendorName)
    {
        $glue = ',';
        if (!empty($paramData['custom']['glue'])) {
            $glue = $paramData['custom']['glue'];
        }
        if (is_array($value)) {
            $this->setListArrayValue($paramData, $value, $vendorName, $glue);
        } else {
            $this->setListStringValue($paramData, $value, $vendorName, $glue);
        }
    }

    /**
     * @param array  $paramData
     * @param array  $value
     * @param string $vendorName
     * @param string $glue
     */
    private function setListArrayValue($paramData, $value, $vendorName, $glue)
    {
        if (!empty($paramData['custom']['toString'])) {
            $value = implode($glue, $value);
        }
        $this->setSingleValidData($paramData, $value, $vendorName);
    }

    /**
     * @param array  $paramData
     * @param string $value
     * @param string $vendorName
     * @param string $glue
     */
    private function setListStringValue($paramData, $value, $vendorName, $glue)
    {
        if (!empty($paramData['custom']['toArray'])) {
            $value = explode($glue, $value);
            if (mb_strtolower($paramData['structure']['type']) == 'number') {
                $value = array_map(function(&$item) {
                    return (int) $item;
                }, $value);
            }
        }
        $this->setSingleValidData($paramData, $value, $vendorName);
    }

    private function setBooleanValue($paramData, $value, $vendorName)
    {
        $data = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        if (!empty($paramData['custom']['toInt'])) {
            $data = (int) $data;
        }
        if (!empty($paramData['custom']['toString'])) {
            $data = $data ? "true" : "false";
        }
        $this->setSingleValidData($paramData, $data, $vendorName);
    }

    private function setIntValue($paramData, $value, $vendorName)
    {
        $data = (int) $value;
        $this->setSingleValidData($paramData, $data, $vendorName);
    }

    private function setDateTimeValue($paramData, $value, $vendorName)
    {
        // todo check if fromFormat.count == 1 and toFormat == fromFormat[0] -> send data to vendor
        $date = false;
        if (!empty($paramData['custom']['dateTime']['fromFormat'])) {
            foreach ($paramData['custom']['dateTime']['fromFormat'] as $format) {
                if ($format == 'unixtime') {
                    $date = new \DateTime();
                    $date->setTimestamp($value);
                } else {
                    $date = \DateTime::createFromFormat($format, $value);
                }
                if ($date instanceof \DateTime) {
                    break;
                }
            }
        } else {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
        }
        if (!$date instanceof \DateTime) {
            if (!empty($paramData['custom']['dateTime']['fromFormat'])) {
                $formatList = implode(',', $paramData['custom']['dateTime']['fromFormat']);
            }
            else {
                $formatList = 'Y-m-d H:i:s';
            }
            throw new PackageException("Check " . $paramData["name"] .". This value can be in formats: " . $formatList, PackageException::DATETIME_FORMAT_CODE);
        }

        if (!empty($paramData['custom']['dateTime']['toFormat'])) {
            if ($paramData['custom']['dateTime']['toFormat'] == 'unixtime') {
                $result = $date->getTimestamp();
            } else {
                $result = $date->format($paramData['custom']['dateTime']['toFormat']);
            }
        } else {
            $result = $date->format('Y-m-d H:i:s');
        }
        $this->setSingleValidData($paramData, $result, $vendorName);
    }

    private function setMapValue($paramData, $value, $vendorName) {
        $value = str_replace(" ", "", $value);
        if (!empty($paramData['custom']['divide'])) {
            $valueAsArray = explode(',', $value);
            if (!empty($paramData['custom']['toFloat'])) {
                if (!empty($paramData['custom']['floatLength'])) {
                    $valueAsArray = $this->toFloatWithLength($valueAsArray, $paramData['custom']['floatLength']);
                }
                $valueAsArray = $this->toFloat($valueAsArray);
            }
            if (!empty($paramData['custom']['lat']) && !empty($paramData['custom']['lng'])) {
                $this->setSingleValidData([], $valueAsArray[0], $paramData['custom']['lat']);
                $this->setSingleValidData([], $valueAsArray[1], $paramData['custom']['lng']);
            }
            else {
                $this->setSingleValidData($paramData, $valueAsArray, $vendorName);
            }
        }
        else {
            $this->setSingleValidData($paramData, $value, $vendorName);
        }
    }

    private function toFloat($value) {
        if (is_array($value)) {
            if (!$this->isMultiDimensionalArray($value)) {
                $value = array_map(function($item) {
                    return filter_var($item, FILTER_VALIDATE_FLOAT);
                }, $value);
            }
        }
        else {
            $value = filter_var($value, FILTER_VALIDATE_FLOAT);
        }

        return $value;
    }

    private function toFloatWithLength($value, $length) {
        if (is_array($value)) {
            foreach ($value as $key => &$val) {
                $val = $this->toFloatWithLength($val, $length);
            }
        }
        else {
            $value = number_format(filter_var($value, FILTER_VALIDATE_FLOAT), $length);
        }

        return $value;
    }


    private function checkBlockMetadata()
    {
        if (!isset($this->blockMetadata['custom']['url'])) {
            throw new PackageException("Cant find vendor's endpoint", PackageException::URL_CODE);
        }
        if (!isset($this->blockMetadata['custom']['method'])) {
            throw new PackageException("Cant find method of vendor's endpoint", PackageException::METHOD_CODE);
        }
    }

    private function normalizeJson($jsonString)
    {
        $data = preg_replace_callback('~"([\[{].*?[}\]])"~s', function ($match) {
            return preg_replace('~\s*"\s*~', "\"", $match[1]);
        }, $jsonString);

        return str_replace('\"', '"', $data);
    }

    private function getValueFromRequestData($paramName)
    {
        if (isset($this->dataFromRequest['args'][$paramName])) {
            return $this->dataFromRequest['args'][$paramName];
        }
        return null;
    }

    private function getMultipartData($data)
    {
        $result = [];
        foreach ($data as $key => $value) {
            $result[] = [
                "name" => $key,
                "contents" => $value
            ];
        }

        return $result;
    }

    private function getBinaryData($data)
    {
        return array_pop($data);
    }

    /**
     * Create array with headers and params for Guzzle
     * @param array  $headers
     * @param array  $urlParam
     * @param array  $params
     * @param string $url
     * @return array
     */
    public function createGuzzleData($url, $headers, $urlParam, $params)
    {
        // todo refactor
        $formParamsFlag = false;
        $method = mb_strtoupper($this->blockMetadata['custom']['method']);
        $result = [];
        $result['headers'] = $headers;
        $result['method'] = $method;
        $result['url'] = $url;

        foreach ($headers as $headerName => $headerValue) {
            if (mb_strtolower($headerName) == 'content-type' && mb_strtolower($headerValue) == 'application/x-www-form-urlencoded') {
                $formParamsFlag = true;
            }
        }

        if ($method == 'GET') {
            $result['query'] = array_merge($params, $urlParam);
        } else {
            if (!empty($urlParam)) {
                $result['query'] = $urlParam;
            }

            if ($formParamsFlag) {
                $result['form_params'] = $params;
            } else {
                if (isset($this->blockMetadata['custom']['type'])) {
                    $type = mb_strtoupper($this->blockMetadata['custom']['type']);
                } else {
                    $type = 'JSON';
                }
                switch ($type) {
                    case 'BINARY':
                        $result['body'] = $this->getBinaryData($params);
                        break;
                    case 'JSON':
                        $result['json'] = $params;
                        break;
                    case 'MULTIPART':
                        $result['multipart'] = $this->getMultipartData($params);
                        break;
                    default:
                        $result['json'] = $params;
                        break;
                }
            }
        }

        return $result;
    }
}