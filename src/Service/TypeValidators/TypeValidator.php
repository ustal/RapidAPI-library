<?php
/**
 * Created by PhpStorm.
 * User: ustal
 * Date: 17.06.17
 * Time: 19:01
 */

namespace RapidAPI\Service\TypeValidators;


class TypeValidator
{
    /** @var StringValidator */
    private $stringValidator;

    /** @var ListValidator */
    private $listValidator;

    /** @var ArrayValidator */
    private $arrayValidator;

    /** @var NumberValidator */
    private $numberValidator;

    /** @var JSONValidator */
    private $JSONValidator;

    /** @var FileValidator */
    private $fileValidator;

    /** @var MapValidator */
    private $mapValidator;

    /** @var DatePickerValidator */
    private $datePickerValidator;

    /** @var bool */
    private $isMultipart = false;

    public function __construct() {
        $this->stringValidator = new StringValidator();
        $this->listValidator = new ListValidator();
        $this->arrayValidator = new ArrayValidator();
        $this->booleanValidator = new BooleanValidator();
        $this->numberValidator = new NumberValidator();
        $this->JSONValidator = new JSONValidator();
        $this->fileValidator = new FileValidator();
        $this->mapValidator = new MapValidator();
        $this->datePickerValidator = new DatePickerValidator();
    }

    public function setMultipart(bool $multipart) {
        $this->isMultipart = $multipart;
    }

    public function save($paramData, $value, $vendorName, $type) {
        switch ($type) {
            case 'json':
                $validator = $this->JSONValidator;
                break;
            case 'array':
                $validator = $this->arrayValidator;
                break;
            case 'list':
                $validator = $this->listValidator;
                break;
            case 'boolean':
                $validator = $this->booleanValidator;
                break;
            case 'number':
                $validator = $this->numberValidator;
                break;
            case 'file':
                $validator = $this->fileValidator;
                break;
            case "datepicker":
                $validator = $this->datePickerValidator;
                break;
            case "map":
                $validator = $this->mapValidator;
                break;
            default:
                $validator = $this->stringValidator;
                break;
        }

        $validator->save($paramData, $value, $vendorName, $this->isMultipart);
    }
}