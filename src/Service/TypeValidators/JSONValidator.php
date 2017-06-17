<?php
/**
 * Created by PhpStorm.
 * User: ustal
 * Date: 17.06.17
 * Time: 19:15
 */

namespace RapidAPI\Service\TypeValidators;


use RapidAPI\Exception\PackageException;

class JSONValidator extends AbstractValidator implements TypeValidatorInterface
{
    public function save($paramData, $value, $vendorName, $multipart=false)
    {
        if (!is_array($value)) {
            $normalizeJson = $this->normalizeJson($value);
            $data = json_decode($normalizeJson, true);
            if (json_last_error()) {
                throw new PackageException("Parse error in: " . $paramData['name'], PackageException::JSON_VALIDATION_CODE);
            } else {
                $this->setSingleValidData($paramData, $data, $vendorName);
            }
        } else {
            $this->setSingleValidData($paramData, $value, $vendorName);
        }
    }

    protected function normalizeJson($jsonString)
    {
        $data = preg_replace_callback('~"([\[{].*?[}\]])"~s', function ($match) {
            return preg_replace('~\s*"\s*~', "\"", $match[1]);
        }, $jsonString);

        return str_replace('\"', '"', $data);
    }
}