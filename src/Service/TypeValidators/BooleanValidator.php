<?php
/**
 * Created by PhpStorm.
 * User: ustal
 * Date: 17.06.17
 * Time: 19:13
 */

namespace RapidAPI\Service\TypeValidators;


class BooleanValidator extends AbstractValidator implements TypeValidatorInterface
{
    public function save($paramData, $value, $vendorName, $multipart=false)
    {
        $data = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        if (!empty($paramData['custom']['toInt'])) {
            $data = (int) $data;
        }
        if (!empty($paramData['custom']['toString'])) {
            $data = $data ? "true" : "false";
        }
        $this->setSingleValidData($paramData, $data, $vendorName);
    }
}