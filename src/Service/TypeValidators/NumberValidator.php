<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 17.06.17
 * Time: 19:14
 */

namespace RapidAPI\Service\TypeValidators;


class NumberValidator extends AbstractValidator implements TypeValidatorInterface
{
    public function parse($paramData, $value, $vendorName, $multipart=false)
    {
        return (int) $value;
    }
}