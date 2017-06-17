<?php
/**
 * Created by PhpStorm.
 * User: ustal
 * Date: 17.06.17
 * Time: 19:10
 */

namespace RapidAPI\Service\TypeValidators;


class ArrayValidator extends AbstractValidator implements TypeValidatorInterface
{
    public function save($paramData, $value, $vendorName, $multipart=false)
    {
        if (!empty($paramData['custom']['keyValue']) && !empty($paramData['custom']['keyValue']['key'] && !empty($paramData['custom']['keyValue']['value']))) {
            $newArray = [];
            foreach ($value as $array) {
                $newArray[$array[$paramData['custom']['keyValue']['key']]] = $array[$paramData['custom']['keyValue']['value']];
            }
            $value = $newArray;
        }
        $this->setSingleValidData($paramData, $value, $vendorName);
    }
}