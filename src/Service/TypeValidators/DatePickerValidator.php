<?php
/**
 * Created by PhpStorm.
 * User: ustal
 * Date: 17.06.17
 * Time: 19:28
 */

namespace RapidAPI\Service\TypeValidators;


use RapidAPI\Exception\PackageException;

class DatePickerValidator extends AbstractValidator implements TypeValidatorInterface
{
    public function save($paramData, $value, $vendorName, $multipart=false)
    {
        // todo check if fromFormat.count == 1 and toFormat == fromFormat[0] -> send data to vendor
        // todo if empty(fromFormat) and empty(toFormat) -> send as string
        //
        $date = false;
        if (!empty($paramData['custom']['dateTime']['fromFormat'])) {
            foreach ($paramData['custom']['dateTime']['fromFormat'] as $format) {
                if ($format == 'unixtime') {
                    $date = new \DateTime();
                    $date->setTimestamp($value);
                } else {
                    $date = \DateTime::createFromFormat($format, $value);
                }
                if ($date instanceof \DateTime) {
                    break;
                }
            }
        } else {
            $date = \DateTime::createFromFormat('Y-m-d H:i:s', $value);
        }
        if (!$date instanceof \DateTime) {
            // todo move out
            if (!empty($paramData['custom']['dateTime']['fromFormat'])) {
                $formatList = implode(',', $paramData['custom']['dateTime']['fromFormat']);
            } else {
                $formatList = 'Y-m-d H:i:s';
            }
            throw new PackageException("Check " . $paramData["name"] . ". This value can be in formats: " . $formatList, PackageException::DATETIME_FORMAT_CODE);
        }

        if (!empty($paramData['custom']['dateTime']['toFormat'])) {
            if ($paramData['custom']['dateTime']['toFormat'] == 'unixtime') {
                $result = $date->getTimestamp();
            } else {
                $result = $date->format($paramData['custom']['dateTime']['toFormat']);
            }
        } else {
            $result = $date->format('Y-m-d H:i:s');
        }
        $this->setSingleValidData($paramData, $result, $vendorName);
    }
}