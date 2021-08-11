<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 04/02/2020
 * Time: 15:37
 */

namespace Respect\Validaton\Wrapper;

use Respect\Validation\Rules;
use Respect\Validation\Rules\AbstractComposite;
use Respect\Validation\Rules\AbstractRule;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Rules\Alnum;
use Respect\Validation\Rules\BoolType;
use Respect\Validation\Rules\Date;
use Respect\Validation\Rules\Equals;
use Respect\Validation\Rules\In;
use Respect\Validation\Rules\Not;
use Respect\Validation\Rules\NullType;
use Respect\Validation\Rules\Numeric;
use Respect\Validation\Rules\OneOf;
use Respect\Validation\Rules\Regex;
use Respect\Validation\Validatable;
use RuntimeException;

/**
 * Trait RespectValidationWrapperTrait
 * @package App\Validator
 */
trait RespectValidationWrapperTrait
{
    /**
     * @return \Respect\Validation\Rules\AllOf
     */
    protected static function isId(): AllOf
    {
        return self::isAllOf([
            new Rules\IntVal(),
            new Rules\Positive()
        ]);
    }

    /**
     * @param array<\Respect\Validation\Rules\AbstractRule> $rules
     * @return \Respect\Validation\Rules\AllOf
     */
    protected static function isAllOf(array $rules): AllOf
    {
        return new AllOf($rules);
    }

    /**
     * @return \Respect\Validation\Rules\Numeric
     */
    protected static function isNumeric(): Numeric
    {
        return new Numeric();
    }

    /**
     * @param int $maxMin
     * @param int $max
     * @return \Respect\Validation\Rules\AbstractRule
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    protected static function isText(int $maxMin = 128, int $max = -1): AbstractComposite
    {
        if ($max <= 0) {
            return new OneOf(
                new Equals(''),
                new AllOf(
                    new Rules\StringType(),
                    new Rules\Length(null, $maxMin)
                )
            );
        }
        return self::isAllOf([
            new Rules\StringType(),
            new Rules\NotEmpty(),
            new Rules\Length($maxMin, $max)
        ]);
    }

    /**
     * @param int $maxMin
     * @param int $max
     * @return \Respect\Validation\Rules\AllOf|\Respect\Validation\Rules\OneOf
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    protected static function isAlphaNum(int $maxMin = 128, int $max = -1)
    {
        if ($max <= 0) {
            return new OneOf(
                new Equals(''),
                self::isAllOf([
                    new Alnum(),
                    new Rules\Length(null, $maxMin)
                ])
            );
        }
        return self::isAllOf([
            new Alnum(),
            new Rules\NotEmpty(),
            new Rules\Length($maxMin, $max)
        ]);
    }

    /**
     * @return \Respect\Validation\Rules\Regex
     */
    protected static function isUid(): Regex
    {
        return new Regex('/[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}/');
    }

    /**
     * @return \Respect\Validation\Rules\BoolType
     */
    protected static function isBool(): BoolType
    {
        return new BoolType();
    }

    /**
     * @return \Respect\Validation\Rules\AllOf
     */
    protected static function isTrue(): AllOf
    {
        return self::isAllOf([
            new BoolType(),
            new Rules\TrueVal()
        ]);
    }

    /**
     * @return \Respect\Validation\Rules\AllOf
     */
    protected static function isFalse(): AllOf
    {
        return self::isAllOf([
            new BoolType(),
            new Rules\FalseVal()
        ]);
    }

    /**
     * @return \Respect\Validation\Rules\Alnum
     */
    protected static function isTelNum(): Alnum
    {
        return new Alnum('+.-/ ');
    }

    /**
     * @return \Respect\Validation\Rules\Date
     */
    protected static function isDate(): Date
    {
        return new Date('Y-m-d');
    }

    /**
     * @return \Respect\Validation\Rules\Date
     */
    protected static function isDateTime(): Date
    {
        return new Date('Y-m-d H:i:s');
    }

    /**
     * @return \Respect\Validation\Rules\Date
     */
    protected static function isTime(): Date
    {
        return new Date('H:i:s');
    }

    /**
     * @return \Respect\Validation\Rules\NullType
     */
    protected static function isNull(): NullType
    {
        return new NullType();
    }

    /**
     * @return \Respect\Validation\Rules\OneOf
     */
    protected static function isNullOrEmpty(): OneOf
    {
        return new OneOf(
            new NullType(),
            new Equals(''),
            new Equals(0)
        );
    }

    /**
     * rend une règle de validation nullable
     * @param AbstractRule $rules
     * @return \Respect\Validation\Rules\OneOf
     */
    protected static function isNullable(AbstractRule $rules): OneOf
    {
        return new OneOf(
            new NullType(),
            $rules
        );
    }

    /**
     * rend une règle de validation nullable
     * et accepte une chaine vide
     * @param AbstractRule $rules
     * @return \Respect\Validation\Rules\OneOf
     */
    protected static function isNullableOrEmpty(AbstractRule $rules): OneOf
    {
        return new OneOf(
            new NullType(),
            new Equals(''),
            new Equals(0),
            $rules
        );
    }

    /**
     * @param \Respect\Validation\Rules\AbstractRule|null $rules
     * @return \Respect\Validation\Rules\AllOf
     */
    protected static function isArray(?AbstractRule $rules = null): AllOf
    {
        $finalRules = [
            new Rules\ArrayType(),
        ];
        if ($rules !== null) {
            $finalRules [] = new Rules\Each($rules);
        }
        return self::isAllOf($finalRules);
    }

    /**
     * @param array<string,\Respect\Validation\Validatable|null> $rules
     * @return \Respect\Validation\Rules\AllOf
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    protected static function isAssocArray(array $rules): AllOf
    {
        $finalRules = [
            new Rules\ArrayType()
        ];
        foreach ($rules as $key => $rule) {
            if (!is_string($key)) {
                throw new RuntimeException(
                    "Impossible d'initialisé la règle isAssocArray. $key n'est pas une clé valide"
                );
            }
            if ($rule !== null && !is_a($rule, Validatable::class)) {
                throw new RuntimeException(
                    "Impossible d'initialisé la règle isAssocArray. la règle de $key n'est pas valide"
                );
            }
            $keyName = trim($key, '?');
            $mandatory = '?' !== $key[-1];
            $finalRules[] = new Rules\Key($keyName, $rule, $mandatory);
        }
        return self::isAllOf($finalRules);
    }

    /**
     * @param array<string,\Respect\Validation\Validatable|null> $rules
     * @param string $className
     * @return \Respect\Validation\Rules\AllOf
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    protected static function isObject(array $rules, string $className = ""): AllOf
    {
        $finalRules = [
            new Rules\ObjectType()
        ];
        if ($className !== '') {
            $finalRules[] = new Rules\Instance($className);
        }
        foreach ($rules as $key => $rule) {
            if (!is_string($key)) {
                throw new RuntimeException(
                    ("Impossible d'initialisé la règle isObject. $key n'est pas une clé valide")
                );
            }
            if ($rule !== null && !is_a($rule, Validatable::class)) {
                throw new RuntimeException(
                    ("Impossible d'initialisé la règle isObject. la règle de $key n'est pas valide")
                );
            }
            $keyName = trim($key, '?');
            $mandatory = '?' !== $key[-1];

            $finalRules[] = new Rules\Attribute($keyName, $rule, $mandatory);
        }
        return self::isAllOf($finalRules);
    }

    /**
     * @param array<\Respect\Validation\Rules\AbstractRule> $rules
     * @return \Respect\Validation\Rules\OneOf
     */
    protected static function isOneOf(array $rules): OneOf
    {
        return new OneOf($rules);
    }

    /**
     * @param \Respect\Validation\Rules\AbstractRule $rule
     * @return \Respect\Validation\Rules\Not
     */
    protected static function not(AbstractRule $rule): Not
    {
        return new Not($rule);
    }

    /**
     * @param mixed $expected
     * @return \Respect\Validation\Rules\Equals
     */
    protected static function is($expected): Equals
    {
        return new Equals($expected);
    }

    /**
     * @param array<\Respect\Validation\Rules\AbstractRule> $array
     * @return \Respect\Validation\Rules\In
     */
    protected static function isIn(array $array): In
    {
        return new In($array);
    }

    /**
     * valide une adresse e-mail
     * @param int $length
     * @return \Respect\Validation\Rules\AbstractComposite
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    protected static function isMail(int $length = 128): AbstractComposite
    {
        return new AllOf(
            new Rules\Length(null, $length),
            new Rules\Email()
        );
    }
}
