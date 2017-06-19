<?php

/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 17.06.17
 * Time: 17:54
 */

namespace RapidAPI\Service\TypeValidators;

abstract class AbstractValidator
{
    protected $urlParams;

    protected $bodyParams;

    /** @var array */
    protected $blockMetadata = [];

    public function getBodyParams() {
        return $this->bodyParams;
    }

    public function getUrlParams() {
        return $this->urlParams;
    }

//    protected function setSingleValidData($paramData, $value, $vendorName)
//    {
//        if (!empty($paramData['custom']['urlParam'])) {
//            $this->setSingleValidVariable($this->urlParams, $value, $vendorName, $paramData);
//        } else {
//            $this->setSingleValidVariable($this->bodyParams, $value, $vendorName, $paramData);
//        }
//    }

//    protected function setSingleValidVariable(&$data, $value, $vendorName, $paramData)
//    {
//        if (!empty($paramData['custom']['wrapName'])) {
//            $wrapNameList = explode('.', $paramData['custom']['wrapName']);
//            $this->addDepthOfNesting($data, $wrapNameList, $value, $vendorName, $paramData);
//        } else {
//            if (!empty($paramData['custom']['complex'])) {
//                $data[$vendorName] = $this->createComplexValue($paramData, $value, $vendorName);
//            } else {
//                $data[$vendorName] = $value;
//            }
//        }
//    }

//    protected function addDepthOfNesting(array &$array, &$depthNameList, $value, $vendorName, $paramData)
//    {
//        $result = [];
//        while (!empty($depthNameList)) {
//            $deepName = array_shift($depthNameList);
//            if (!isset($array[$deepName]) && !empty($depthNameList)) {
//                $array[$deepName] = [];
//            }
//            if (empty($depthNameList)) {
//                if (!empty($paramData['custom']['complex'])) {
//                    $array[$deepName][] = $this->createComplexValue($paramData, $value, $vendorName);
//                } else {
//                    $array[$deepName][$vendorName] = $value;
//                }
//            }
//            $result = $this->addDepthOfNesting($array[$deepName], $depthNameList, $value, $vendorName, $paramData);
//        }
//
//        return $result;
//    }
//
//    protected function createComplexValue($paramData, $value, $vendorName)
//    {
//        return [
//            $paramData['custom']['keyName'] => $vendorName,
//            $paramData['custom']['valueName'] => $value
//        ];
//    }
}