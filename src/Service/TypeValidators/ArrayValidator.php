<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 17.06.17
 * Time: 19:10
 */

namespace RapidAPI\Service\TypeValidators;


class ArrayValidator extends AbstractValidator implements TypeValidatorInterface
{
    public function parse($paramData, $value, $vendorName, $multipart = false)
    {
        if (!empty($paramData['custom']['keyValue']) && !empty($paramData['custom']['keyValue']['key'] && !empty($paramData['custom']['keyValue']['value']))) {
            return $this->createKeyValue($paramData, $value);
        }

        return $value;
    }

    protected function createKeyValue($paramData, $value)
    {
        $result = [];
        foreach ($value as $array) {
            $result[$array[$paramData['custom']['keyValue']['key']]] = $array[$paramData['custom']['keyValue']['value']];
        }

        return $result;
    }
}