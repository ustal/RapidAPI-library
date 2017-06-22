<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 17.06.17
 * Time: 19:15
 */

namespace RapidAPI\Service\TypeValidators;


use RapidAPI\Exception\PackageException;

class JSONValidator implements TypeValidatorInterface
{
    public function parse($paramData, $value, $vendorName, $multipart=false)
    {
        if (!is_array($value)) {
            $normalizeJson = $this->normalizeJson($value);
            $data = json_decode($normalizeJson, true);
            if (json_last_error()) {
                throw new PackageException("Parse error in: " . $paramData['name'], PackageException::JSON_VALIDATION_CODE);
            } else {
                return $data;
            }
        } else {
            return $value;
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
