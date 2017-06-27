<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 17.06.17
 * Time: 19:01
 */

namespace RapidAPI\Service\TypeValidators;


class TypeValidator
{
    /** @var StringValidator */
    private $stringValidator;

    /** @var ListValidator */
    private $listValidator;

    /** @var ArrayValidator */
    private $arrayValidator;

    /** @var NumberValidator */
    private $numberValidator;

    /** @var JSONValidator */
    private $JSONValidator;

    /** @var FileValidator */
    private $fileValidator;

    /** @var MapValidator */
    private $mapValidator;

    /** @var DatePickerValidator */
    private $datePickerValidator;

    /** @var bool */
    private $isMultipart = false;

    private $urlParams = [];

    private $bodyParams = [];

    public function __construct()
    {
        $this->stringValidator = new StringValidator();
        $this->listValidator = new ListValidator();
        $this->arrayValidator = new ArrayValidator();
        $this->booleanValidator = new BooleanValidator();
        $this->numberValidator = new NumberValidator();
        $this->JSONValidator = new JSONValidator();
        $this->fileValidator = new FileValidator();
        $this->mapValidator = new MapValidator();
        $this->datePickerValidator = new DatePickerValidator();
    }

    public function getUrlParams(): array
    {
        return $this->urlParams;
    }

    public function getBodyParams(): array
    {
        return $this->bodyParams;
    }

    public function setMultipart(bool $multipart)
    {
        $this->isMultipart = $multipart;
    }

    public function save($paramData, $value, $vendorName, $type)
    {
        switch ($type) {
            case 'json':
                $data = $this->JSONValidator->parse($paramData, $value, $vendorName);
                break;
            case 'array':
                $data = $this->arrayValidator->parse($paramData, $value, $vendorName);
                break;
            case 'list':
                $data = $this->listValidator->parse($paramData, $value, $vendorName);
                break;
            case 'boolean':
                $data = $this->booleanValidator->parse($paramData, $value, $vendorName);
                break;
            case 'number':
                $data = $this->numberValidator->parse($paramData, $value, $vendorName);
                break;
            case 'file':
                $data = $this->fileValidator->parse($paramData, $value, $vendorName, $this->isMultipart);
                break;
            case "datepicker":
                $data = $this->datePickerValidator->parse($paramData, $value, $vendorName);
                break;
            case "map":
                $data = $this->mapValidator->parse($paramData, $value, $vendorName);
                break;
            default:
                $data = $this->stringValidator->parse($paramData, $value, $vendorName);
                break;
        }
        $this->setSingleValidData($paramData, $data, $vendorName);
    }

    protected function setSingleValidData($paramData, $value, $vendorName)
    {
        if (!empty($paramData['custom']['urlParam'])) {
            $this->setSingleValidVariable($this->urlParams, $value, $vendorName, $paramData);
        } else {
            $this->setSingleValidVariable($this->bodyParams, $value, $vendorName, $paramData);
        }
    }

    protected function setSingleValidVariable(&$data, $value, $vendorName, $paramData)
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

    protected function addDepthOfNesting(array &$array, &$depthNameList, $value, $vendorName, $paramData)
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

    protected function createComplexValue($paramData, $value, $vendorName)
    {
        return [
            $paramData['custom']['keyName'] => $vendorName,
            $paramData['custom']['valueName'] => $value
        ];
    }
}