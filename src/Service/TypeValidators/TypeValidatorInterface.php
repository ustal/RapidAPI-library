<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 17.06.17
 * Time: 18:57
 */

namespace RapidAPI\Service\TypeValidators;


interface TypeValidatorInterface
{
    public function parse($paramData, $value, $vendorName, $multipart=false);
}