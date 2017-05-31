<?php

namespace RapidAPI\Exception;

class PackageException extends \Exception
{
    const URL_CODE = 1;
    const METHOD_CODE = 2;
    const JSON_VALIDATION_CODE = 3;
    const BLOCK_NOT_EXIST_CODE = 4;
}