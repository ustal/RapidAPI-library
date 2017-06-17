<?php
/**
 * Created by PhpStorm.
 * User: ustal
 * Date: 17.06.17
 * Time: 19:08
 */

namespace RapidAPI\Service\TypeValidators;


class ListValidator extends AbstractValidator implements TypeValidatorInterface
{
    public function save($paramData, $value, $vendorName, $multipart=false)
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

    protected function setListArrayValue($paramData, $value, $vendorName, $glue)
    {
        if (!empty($paramData['custom']['toString'])) {
            $value = implode($glue, $value);
        }
        $this->setSingleValidData($paramData, $value, $vendorName);
    }

    protected function setListStringValue($paramData, $value, $vendorName, $glue)
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
}