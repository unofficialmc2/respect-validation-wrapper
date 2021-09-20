<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 04/02/2020
 * Time: 15:37
 */

namespace Respect\Validaton\Wrapper;

use LogicException;
use Respect\Validation\Rules;
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
     * @return \Respect\Validation\Validatable
     */
    protected static function isId(): Validatable
    {
        return new AllOf(
            new Rules\IntVal(),
            new Rules\Positive()
        );
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isNumeric(): Validatable
    {
        return new Numeric();
    }

    /**
     * @param int $maxMin
     * @param int $max
     * @return \Respect\Validation\Validatable
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    protected static function isText(int $maxMin = 128, int $max = -1): Validatable
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
        return new AllOf(
            new Rules\StringType(),
            new Rules\NotEmpty(),
            new Rules\Length($maxMin, $max)
        );
    }

    /**
     * @param int $maxMin
     * @param int $max
     * @return \Respect\Validation\Validatable
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    protected static function isAlphaNum(int $maxMin = 128, int $max = -1): Validatable
    {
        if ($max <= 0) {
            return new OneOf(
                new Equals(''),
                new AllOf(
                    new Alnum(),
                    new Rules\Length(null, $maxMin)
                )
            );
        }
        return new AllOf(
            new Alnum(),
            new Rules\NotEmpty(),
            new Rules\Length($maxMin, $max)
        );
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isUid(): Validatable
    {
        return new Regex('/[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}/');
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isBool(): Validatable
    {
        return new BoolType();
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isTrue(): Validatable
    {
        return new AllOf(
            new BoolType(),
            new Rules\TrueVal()
        );
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isFalse(): Validatable
    {
        return new AllOf(
            new BoolType(),
            new Rules\FalseVal()
        );
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isTelNum(): Validatable
    {
        return new Alnum('+.-/ ');
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isDate(): Validatable
    {
        return new Date('Y-m-d');
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isDateTime(): Validatable
    {
        return new Date('Y-m-d H:i:s');
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isTime(): Validatable
    {
        return new Date('H:i:s');
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isNull(): Validatable
    {
        return new NullType();
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isNullOrEmpty(): Validatable
    {
        return new OneOf(
            new NullType(),
            new Equals(''),
            new Equals(0)
        );
    }

    /**
     * rend une règle de validation nullable
     * @param \Respect\Validation\Validatable $rules
     * @return \Respect\Validation\Validatable
     */
    protected static function isNullable(Validatable $rules): Validatable
    {
        return new OneOf(
            new NullType(),
            $rules
        );
    }

    /**
     * rend une règle de validation nullable
     * et accepte une chaine vide
     * @param \Respect\Validation\Validatable $rules
     * @return \Respect\Validation\Validatable
     */
    protected static function isNullableOrEmpty(Validatable $rules): Validatable
    {
        return new OneOf(
            new NullType(),
            new Equals(''),
            new Equals(0),
            $rules
        );
    }

    /**
     * @param \Respect\Validation\Validatable|null $rules
     * @return \Respect\Validation\Validatable
     */
    protected static function isArray(?Validatable $rules = null): Validatable
    {
        $finalRules = [
            new Rules\ArrayType(),
        ];
        if ($rules !== null) {
            $finalRules [] = new Rules\Each($rules);
        }
        return new AllOf(...$finalRules);
    }

    /**
     * @param array<string,\Respect\Validation\Validatable|null> $rules
     * @return \Respect\Validation\Validatable
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    protected static function isAssocArray(array $rules): Validatable
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
            $keyName = trim($key, '?-');
            $mandatory = '?' !== $key[-1];
            $not = '-' === $key[0];
            if (!$mandatory && $not) {
                throw new LogicException("Les potions de clé ne sont pas compatible");
            }
            $finalRules[] = !$not
                ? new Rules\Key($keyName, $rule, $mandatory)
                : new Not(new Rules\Key($keyName));
        }
        return new AllOf(...$finalRules);
    }

    /**
     * @param array<string,\Respect\Validation\Validatable|null> $rules
     * @param string $className
     * @return \Respect\Validation\Validatable
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    protected static function isObject(array $rules, string $className = ""): Validatable
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
            $keyName = trim($key, '?-');
            $mandatory = '?' !== $key[-1];
            $not = '-' === $key[0];
            if (!$mandatory && $not) {
                throw new LogicException("Les potions d'attributs ne sont pas compatible");
            }
            $finalRules[] = !$not
                ? new Rules\Attribute($keyName, $rule, $mandatory)
                : new Not(new Rules\Attribute($keyName));
        }
        return new AllOf(...$finalRules);
    }

    /**
     * @param array<\Respect\Validation\Validatable> $rules
     * @return \Respect\Validation\Validatable
     */
    protected static function isAllOf(array $rules): Validatable
    {
        return new AllOf($rules);
    }

    /**
     * @param array<\Respect\Validation\Validatable> $rules
     * @return \Respect\Validation\Validatable
     */
    protected static function isOneOf(array $rules): Validatable
    {
        return new OneOf($rules);
    }

    /**
     * inverse la validation
     * @param \Respect\Validation\Validatable $rule
     * @return \Respect\Validation\Validatable
     */
    protected static function not(Validatable $rule): Validatable
    {
        return new Not($rule);
    }

    /**
     * @param mixed $expected
     * @return \Respect\Validation\Validatable
     */
    protected static function is($expected): Validatable
    {
        return new Equals($expected);
    }

    /**
     * @param array<\Respect\Validation\Validatable> $array
     * @return \Respect\Validation\Validatable
     */
    protected static function isIn(array $array): Validatable
    {
        return new In($array);
    }

    /**
     * valide une adresse e-mail
     * @param int $length
     * @return \Respect\Validation\Validatable
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    protected static function isMail(int $length = 128): Validatable
    {
        return new AllOf(
            new Rules\Length(null, $length),
            new Rules\Email()
        );
    }

    /**
     * valide une URL
     * @return \Respect\Validation\Validatable
     */
    protected static function isUrl(): Validatable
    {
        return new Rules\Url();
    }
}
