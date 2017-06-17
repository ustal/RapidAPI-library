<?php
/**
 * Created by PhpStorm.
 * User: ustal
 * Date: 17.06.17
 * Time: 18:57
 */

namespace RapidAPI\Service\TypeValidators;


interface TypeValidatorInterface
{
    public function save($paramData, $value, $vendorName, $multipart=false);
}