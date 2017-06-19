<?php
/**
 * Created by PhpStorm.
 * User: George Cherenkov
 * Date: 17.06.17
 * Time: 19:29
 */

namespace RapidAPI\Service\TypeValidators;


class MapValidator extends AbstractValidator implements TypeValidatorInterface
{
    public function parse($paramData, $value, $vendorName, $multipart = false)
    {
        $value = str_replace(" ", "", $value);
        if (!empty($paramData['custom']['divide'])) {
            $valueAsArray = explode(',', $value);
            if (!empty($paramData['custom']['toFloat'])) {
                if (!empty($paramData['custom']['floatLength'])) {
                    $valueAsArray = $this->toFloatWithLength($valueAsArray, $paramData['custom']['floatLength']);
                }
                $valueAsArray = $this->toFloat($valueAsArray);
            }
            if (!empty($paramData['custom']['lat']) && !empty($paramData['custom']['lng'])) {
                // todo fix rename of lat and lng
                return $valueAsArray;
//                $this->setSingleValidData([], $valueAsArray[0], $paramData['custom']['lat']);
//                $this->setSingleValidData([], $valueAsArray[1], $paramData['custom']['lng']);
            } else {
                return $valueAsArray;
            }
        } else {
            return $value;
        }
    }

    protected function toFloatWithLength($value, $length)
    {
        if (is_array($value)) {
            foreach ($value as $key => &$val) {
                $val = $this->toFloatWithLength($val, $length);
            }
        } else {
            $value = number_format(filter_var($value, FILTER_VALIDATE_FLOAT), $length);
        }

        return $value;
    }

    protected function toFloat($value)
    {
        if (is_array($value)) {
            $value = array_map(function ($item) {
                return filter_var($item, FILTER_VALIDATE_FLOAT);
            }, $value);
        } else {
            $value = filter_var($value, FILTER_VALIDATE_FLOAT);
        }

        return $value;
    }
}