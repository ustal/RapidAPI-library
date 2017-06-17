<?php
/**
 * Created by PhpStorm.
 * User: ustal
 * Date: 17.06.17
 * Time: 19:19
 */

namespace RapidAPI\Service\TypeValidators;


class FileValidator extends JSONValidator
{
    public function save($paramData, $value, $vendorName, $multipart=false)
    {
        if (!empty($paramData['custom']['jsonParse'])) {
            $content = file_get_contents($value);
            parent::save($paramData, $content, $vendorName);
        } else {
            if ($multipart) {
                $content = fopen($value, 'r');
            } else {
                $content = file_get_contents($value);
                if (isset($paramData['custom']['base64encode']) && filter_var($paramData['custom']['base64encode'], FILTER_VALIDATE_BOOLEAN) == true) {
                    $content = base64_encode($content);
                }
            }
            $this->setSingleValidData($paramData, $content, $vendorName);
        }
    }
}