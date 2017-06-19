<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 17.06.17
 * Time: 18:55
 */

namespace RapidAPI\Service\TypeValidators;


class StringValidator extends AbstractValidator implements TypeValidatorInterface
{
    public function parse($paramData, $value, $vendorName, $multipart=false) {
        return $value;
    }
}