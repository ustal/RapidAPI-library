<?php
/**
 * Created by PhpStorm.
 * User: ustal
 * Date: 17.06.17
 * Time: 18:55
 */

namespace RapidAPI\Service\TypeValidators;


class StringValidator extends AbstractValidator implements TypeValidatorInterface
{
    public function save($paramData, $value, $vendorName, $multipart=false) {
        $this->setSingleValidData($paramData, $value, $vendorName);
    }
}