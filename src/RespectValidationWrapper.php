<?php

namespace Respect\Validaton\Wrapper;

use Respect\Validation\Rules\AbstractComposite;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Rules\Alnum;
use Respect\Validation\Rules\BoolType;
use Respect\Validation\Rules\Date;
use Respect\Validation\Rules\Not;
use Respect\Validation\Rules\Numeric;
use Respect\Validation\Rules\OneOf;
use Respect\Validation\Rules\Regex;

/**
 * Class RespectValidationWrapper
 * @package Respect\Validato\Wrapper
 */
class RespectValidationWrapper
{
    use RespectValidationWrapperTrait;

    /**
     * @return \Respect\Validation\Rules\AllOf
     */
    public static function isId(): AllOf
    {
        return RespectValidationWrapperTrait::isId();
    }

    /**
     * @return \Respect\Validation\Rules\Numeric
     */
    public static function isNumeric(): Numeric
    {
        return RespectValidationWrapperTrait::isNumeric();
    }

    /**
     * @param int $maxMin
     * @param int $max
     * @return \Respect\Validation\Rules\AbstractComposite
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public static function isText(int $maxMin = 128, int $max = -1): AbstractComposite
    {
        return RespectValidationWrapperTrait::isText($maxMin, $max);
    }

    /**
     * @param int $maxMin
     * @param int $max
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public static function isAlphaNum(int $maxMin = 128, int $max = -1)
    {
        return RespectValidationWrapperTrait::isAlphaNum($maxMin, $max);
    }

    /**
     * @return \Respect\Validation\Rules\Regex
     */
    public static function isUid(): Regex
    {
        return RespectValidationWrapperTrait::isUid();
    }

    /**
     * @return \Respect\Validation\Rules\BoolType
     */
    public static function isBool(): BoolType
    {
        return RespectValidationWrapperTrait::isBool();
    }

    /**
     * @return \Respect\Validation\Rules\AllOf
     */
    public static function isTrue(): AllOf
    {
        return RespectValidationWrapperTrait::isTrue();
    }

    /**
     * @return \Respect\Validation\Rules\AllOf
     */
    public static function isFalse(): AllOf
    {
        return RespectValidationWrapperTrait::isFalse();
    }

    /**
     * @return \Respect\Validation\Rules\Alnum
     */
    public static function isTelNum(): Alnum
    {
        return RespectValidationWrapperTrait::isTelNum();
    }

    /**
     * @return \Respect\Validation\Rules\Date
     */
    public static function isDate(): Date
    {
        return RespectValidationWrapperTrait::isDate();
    }

    /**
     * @param $rules
     * @return \Respect\Validation\Rules\OneOf
     */
    public static function isNullable($rules): OneOf
    {
        return RespectValidationWrapperTrait::isNullable($rules);
    }

    /**
     * @param $rules
     * @return \Respect\Validation\Rules\OneOf
     */
    public static function isNullableOrEmpty($rules): OneOf
    {
        return RespectValidationWrapperTrait::isNullableOrEmpty($rules);
    }

    /**
     * @param null $rules
     * @return \Respect\Validation\Rules\AllOf
     */
    public static function isArray($rules = null): AllOf
    {
        return RespectValidationWrapperTrait::isArray($rules);
    }

    /**
     * @param array $rules
     * @return \Respect\Validation\Rules\AllOf
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public static function isAssocArray(array $rules): AllOf
    {
        return RespectValidationWrapperTrait::isAssocArray($rules);
    }

    /**
     * @param array $rules
     * @param string $className
     * @return \Respect\Validation\Rules\AllOf
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public static function isObject(array $rules, string $className = ""): AllOf
    {
        return RespectValidationWrapperTrait::isObject($rules, $className);
    }

    /**
     * @param array $rules
     * @return \Respect\Validation\Rules\AllOf
     */
    public static function isAllOf(array $rules): AllOf
    {
        return RespectValidationWrapperTrait::isAllOf($rules);
    }

    /**
     * @param array $rules
     * @return \Respect\Validation\Rules\OneOf
     */
    public static function isOneOf(array $rules): OneOf
    {
        return RespectValidationWrapperTrait::isOneOf($rules);
    }

    /**
     * @return \Respect\Validation\Rules\Date
     */
    public static function isDateTime(): Date
    {
        return RespectValidationWrapperTrait::isDateTime();
    }

    /**
     * @return \Respect\Validation\Rules\Date
     */
    public static function isTime(): Date
    {
        return RespectValidationWrapperTrait::isTime();
    }

    public static function not($rule): Not
    {
        return RespectValidationWrapperTrait::not($rule);
    }
}