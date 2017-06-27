<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 17.06.17
 * Time: 19:19
 */

namespace RapidAPI\Service\TypeValidators;

/**
 * Class FileValidator
 * @package RapidAPI\Service\TypeValidators
 * @codeCoverageIgnore
 */
class FileValidator extends JSONValidator
{
    public function parse($paramData, $value, $vendorName, $multipart=false)
    {
        if (!empty($paramData['custom']['jsonParse'])) {
            $content = file_get_contents($value);
            // todo check is content exist and not error
            $content = json_decode($content, true);
//            parent::save($paramData, $content, $vendorName);
        } else {
            if ($multipart) {
                $content = fopen($value, 'r');
            } else {
                $content = file_get_contents($value);
                if (isset($paramData['custom']['base64encode']) && filter_var($paramData['custom']['base64encode'], FILTER_VALIDATE_BOOLEAN) == true) {
                    $content = base64_encode($content);
                }
            }
//            $this->setSingleValidData($paramData, $content, $vendorName);
        }
        return $content;
    }
}
