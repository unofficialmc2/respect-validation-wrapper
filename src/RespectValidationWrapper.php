<?php
declare(strict_types=1);

namespace Respect\Validaton\Wrapper;

use Respect\Validation\Validatable;

/**
 * Class RespectValidationWrapper
 * @package Respect\Validato\Wrapper
 */
class RespectValidationWrapper
{
    use RespectValidationWrapperTrait;

    /**
     * @return \Respect\Validation\Validatable
     */
    public static function isId(): Validatable
    {
        return RespectValidationWrapperTrait::isId();
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    public static function isNumeric(): Validatable
    {
        return RespectValidationWrapperTrait::isNumeric();
    }

    /**
     * @param int $maxMin
     * @param int $max
     * @return \Respect\Validation\Validatable
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public static function isText(int $maxMin = 128, int $max = -1): Validatable
    {
        return RespectValidationWrapperTrait::isText($maxMin, $max);
    }

    /**
     * @param int $maxMin
     * @param int $max
     * @return \Respect\Validation\Validatable
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public static function isAlphaNum(int $maxMin = 128, int $max = -1): Validatable
    {
        return RespectValidationWrapperTrait::isAlphaNum($maxMin, $max);
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    public static function isUid(): Validatable
    {
        return RespectValidationWrapperTrait::isUid();
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    public static function isBool(): Validatable
    {
        return RespectValidationWrapperTrait::isBool();
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    public static function isTrue(): Validatable
    {
        return RespectValidationWrapperTrait::isTrue();
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    public static function isFalse(): Validatable
    {
        return RespectValidationWrapperTrait::isFalse();
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    public static function isTelNum(): Validatable
    {
        return RespectValidationWrapperTrait::isTelNum();
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    public static function isDate(): Validatable
    {
        return RespectValidationWrapperTrait::isDate();
    }

    /**
     * @param \Respect\Validation\Validatable $rules
     * @return \Respect\Validation\Validatable
     */
    public static function isNullable(Validatable $rules): Validatable
    {
        return RespectValidationWrapperTrait::isNullable($rules);
    }

    /**
     * @param \Respect\Validation\Validatable $rules
     * @return \Respect\Validation\Validatable
     */
    public static function isNullableOrEmpty(Validatable $rules): Validatable
    {
        return RespectValidationWrapperTrait::isNullableOrEmpty($rules);
    }

    /**
     * @param null $rules
     * @return \Respect\Validation\Validatable
     */
    public static function isArray($rules = null): Validatable
    {
        return RespectValidationWrapperTrait::isArray($rules);
    }

    /**
     * @param array<\Respect\Validation\Validatable> $rules
     * @return \Respect\Validation\Validatable
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public static function isAssocArray(array $rules): Validatable
    {
        return RespectValidationWrapperTrait::isAssocArray($rules);
    }

    /**
     * @param array<\Respect\Validation\Validatable> $rules
     * @param string $className
     * @return \Respect\Validation\Validatable
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public static function isObject(array $rules, string $className = ""): Validatable
    {
        return RespectValidationWrapperTrait::isObject($rules, $className);
    }

    /**
     * @param array<\Respect\Validation\Validatable> $rules
     * @return \Respect\Validation\Validatable
     */
    public static function isAllOf(array $rules): Validatable
    {
        return RespectValidationWrapperTrait::isAllOf($rules);
    }

    /**
     * @param array<\Respect\Validation\Validatable> $rules
     * @return \Respect\Validation\Validatable
     */
    public static function isOneOf(array $rules): Validatable
    {
        return RespectValidationWrapperTrait::isOneOf($rules);
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    public static function isDateTime(): Validatable
    {
        return RespectValidationWrapperTrait::isDateTime();
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    public static function isTime(): Validatable
    {
        return RespectValidationWrapperTrait::isTime();
    }

    /**
     * @param \Respect\Validation\Validatable $rule
     * @return \Respect\Validation\Validatable
     */
    public static function not(Validatable $rule): Validatable
    {
        return RespectValidationWrapperTrait::not($rule);
    }

    /**
     * @param mixed $expected
     * @return \Respect\Validation\Validatable
     */
    protected static function is($expected): Validatable
    {
        return RespectValidationWrapperTrait::is($expected);
    }

    /**
     * @param mixed[] $array
     * @return \Respect\Validation\Validatable
     */
    protected static function isIn(array $array): Validatable
    {
        return RespectValidationWrapperTrait::isIn($array);
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isNull(): Validatable
    {
        return RespectValidationWrapper::isNull();
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isNullOrEmpty(): Validatable
    {
        return RespectValidationWrapper::isNullOrEmpty();
    }

    /**
     * @param int $length
     * @return \Respect\Validation\Validatable
     */
    protected static function isMail(int $length = 128): Validatable
    {
        return RespectValidationWrapper::isMail($length);
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isUrl(): Validatable
    {
        return RespectValidationWrapper::isUrl();
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isUriData(): Validatable
    {
        return RespectValidationWrapper::isUriData();
    }
}
