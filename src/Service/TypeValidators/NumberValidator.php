<?php
/**
 * Created by PhpStorm.
 * User: ustal
 * Date: 17.06.17
 * Time: 19:14
 */

namespace RapidAPI\Service\TypeValidators;


class NumberValidator extends AbstractValidator implements TypeValidatorInterface
{
    public function save($paramData, $value, $vendorName, $multipart=false)
    {
        $data = (int) $value;
        $this->setSingleValidData($paramData, $data, $vendorName);
    }
}