<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 17.06.17
 * Time: 19:28
 */

namespace RapidAPI\Service\TypeValidators;


use RapidAPI\Exception\PackageException;

class DatePickerValidator extends AbstractValidator implements TypeValidatorInterface
{

    /**
     * @var array
     */
    private $formats = [
        'RAPID' => 'Y-m-d H:i:s',
        'ISO8601' => \DateTime::ISO8601,
        'ATOM' => \DateTime::ATOM,
        'COOKIE' => \DateTime::COOKIE,
        'RFC822' => \DateTime::RFC822,
        'RFC850' => \DateTime::RFC850,
        'RFC1036' => \DateTime::RFC1036,
        'RFC1123' => \DateTime::RFC1123,
        'RFC2822' => \DateTime::RFC2822,
        'RFC3339' => \DateTime::RFC3339,
        'RSS' => \DateTime::RSS,
        'W3C' => \DateTime::W3C,
    ];

    /**
     * @param $paramData
     * @param $value
     * @param $vendorName
     * @param bool $multipart
     * @return int|string
     */
    public function parse($paramData, $value, $vendorName, $multipart = false)
    {
        if ($this->isFormatNeeded($paramData)) {
            $date = $this->createDataFromFormat($paramData, $value);
            if (!$date instanceof \DateTime) {
                $this->createDateTimeError($paramData);
            }
            $result = $this->formatData($paramData, $date);
        } else {
            $result = $value;
        }

        return $result;
    }

    /**
     * @param $paramData
     * @return bool
     */
    protected function isFormatNeeded($paramData)
    {
        if ($this->isFormatsEmpty($paramData) || $this->isFormatsTheSame($paramData)) {
            return false;
        }

        return true;
    }

    /**
     * @param $paramData
     * @return bool
     */
    protected function isFormatsEmpty($paramData)
    {
        if (empty($paramData['custom']['dateTime']['fromFormat']) && empty($paramData['custom']['dateTime']['toFormat'])) {
            return true;
        }

        return false;
    }

    /**
     * @param $paramData
     * @return bool
     */
    protected function isFormatsTheSame($paramData)
    {
        if (!empty($paramData['custom']['dateTime']['fromFormat']) &&
            !empty($paramData['custom']['dateTime']['toFormat']) &&
            count($paramData['custom']['dateTime']['fromFormat']) == 1 &&
            $paramData['custom']['dateTime']['fromFormat'][0] == $paramData['custom']['dateTime']['toFormat']
        ) {
            return true;
        }

        return false;
    }

    /**
     * @param $paramData
     * @throws PackageException
     */
    protected function createDateTimeError($paramData)
    {
        if (!empty($paramData['custom']['dateTime']['fromFormat'])) {
            $formatList = implode(',', $paramData['custom']['dateTime']['fromFormat']);
        } else {
            $formatList = 'Y-m-d H:i:s';
        }
        throw new PackageException(
            "Check ".$paramData["name"].". This value can be in formats: ".$formatList,
            PackageException::DATETIME_FORMAT_CODE
        );
    }

    /**
     * @param $format
     * @return string
     */
    protected function getFormatFromPredefined($format)
    {
        if (isset($this->formats[$format])) {
            return $this->formats[$format];
        }

        return $format;
    }

    /**
     * @param $paramData
     * @param $value
     * @return bool|\DateTime
     */
    protected function createDataFromFormat($paramData, $value)
    {
        $date = false;
        if (!empty($paramData['custom']['dateTime']['fromFormat'])) {
            foreach ($paramData['custom']['dateTime']['fromFormat'] as $format) {
                if ($format == 'timestamp') {
                    $date = new \DateTime();
                    $date->setTimestamp($value);
                } else {
                    $date = \DateTime::createFromFormat($this->getFormatFromPredefined($format), $value);
                }
                if ($date instanceof \DateTime) {
                    break;
                }
            }
        } else {
            $date = \DateTime::createFromFormat($this->getFormatFromPredefined('RAPID'), $value);
        }

        return $date;
    }

    /**
     * @param $paramData
     * @param \DateTime $date
     * @return int|string
     */
    protected function formatData($paramData, \DateTime $date) {
        if (!empty($paramData['custom']['dateTime']['toFormat'])) {
            if ($paramData['custom']['dateTime']['toFormat'] == 'timestamp') {
                $result = $date->getTimestamp();
            } else {
                $result = $date->format(
                    $this->getFormatFromPredefined($paramData['custom']['dateTime']['toFormat'])
                );
            }
        } else {
            $result = $date->format($this->getFormatFromPredefined('RAPID'));
        }

        return $result;
    }
}
