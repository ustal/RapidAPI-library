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
        $isFloat = !empty($paramData['custom']['toFloat']);
        $value = str_replace(" ", "", $value);
        if (!empty($paramData['custom']['divide'])) {
            $value = explode(',', $value);
            if ($isFloat) {
                $value = $this->toFloat($value);
            }
            if (!empty($paramData['custom']['lat']) && !empty($paramData['custom']['lng'])) {
                // todo fix rename of lat and lng
                return $value;
//                $this->setSingleValidData([], $valueAsArray[0], $paramData['custom']['lat']);
//                $this->setSingleValidData([], $valueAsArray[1], $paramData['custom']['lng']);
            }
        }
        if (!empty($paramData['custom']['length'])) {
            $value = $this->setLength($value, $paramData['custom']['length'], $isFloat);
        }

        return $value;
    }

    protected function setLength($value, $length, $isFloat) {
        if (is_array($value)) {
            $result =  $this->setLengthArray($value, $length, $isFloat);
        }
        else {
            $array = explode(',', $value);
            $resultArray = $this->setLengthArray($array, $length, $isFloat);
            $result = implode(',', $resultArray);
        }

        return $result;
    }

    protected function setLengthArray($array, $length, $isFloat = true) {
      foreach ($array as &$value) {
          $value = number_format(filter_var($value, FILTER_VALIDATE_FLOAT), $length);
          if ($isFloat) {
              // in documentation says number format return number, but string!!!
              $value = filter_var($value, FILTER_VALIDATE_FLOAT);
          }
      }
      return $array;
    }

    protected function toFloat($value)
    {
        if (is_array($value)) {
            $value = array_map(function ($item) {
                return filter_var($item, FILTER_VALIDATE_FLOAT);
            }, $value);
        }

        return $value;
    }
}
