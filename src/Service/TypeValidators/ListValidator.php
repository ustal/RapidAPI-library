<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 17.06.17
 * Time: 19:08
 */

namespace RapidAPI\Service\TypeValidators;


class ListValidator implements TypeValidatorInterface
{
    public function parse($paramData, $value, $vendorName, $multipart=false)
    {
        $glue = ',';
        if (!empty($paramData['custom']['glue'])) {
            $glue = $paramData['custom']['glue'];
        }
        if (is_array($value)) {
            return $this->getListArrayValue($paramData, $value, $glue);
        } else {
            return $this->getListStringValue($paramData, $value, $glue);
        }
    }

    protected function getListArrayValue($paramData, $value, $glue)
    {
        if (!empty($paramData['custom']['toString'])) {
            $value = implode($glue, $value);
        }
        return $value;
    }

    protected function getListStringValue($paramData, $value, $glue)
    {
        if (!empty($paramData['custom']['toArray'])) {
            $value = explode($glue, $value);
            if (mb_strtolower($paramData['structure']['type']) == 'number') {
                $value = array_map(function(&$item) {
                    return (int) $item;
                }, $value);
            }
        }

        return $value;
    }
}
