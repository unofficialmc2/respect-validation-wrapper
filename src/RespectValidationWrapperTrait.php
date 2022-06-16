<?php
declare(strict_types=1);
/**
 * User: Fabien Sanchez
 * Date: 04/02/2020
 * Time: 15:37
 */

namespace Respect\Validaton\Wrapper;

use LogicException;
use Respect\Validation\Exceptions\ComponentException;
use Respect\Validation\Rules;
use Respect\Validation\Rules\AllOf;
use Respect\Validation\Rules\Alnum;
use Respect\Validation\Rules\AnyOf;
use Respect\Validation\Rules\BoolType;
use Respect\Validation\Rules\Callback;
use Respect\Validation\Rules\Date;
use Respect\Validation\Rules\DateTime;
use Respect\Validation\Rules\Digit;
use Respect\Validation\Rules\Equals;
use Respect\Validation\Rules\Identical;
use Respect\Validation\Rules\In;
use Respect\Validation\Rules\Length;
use Respect\Validation\Rules\Not;
use Respect\Validation\Rules\NullType;
use Respect\Validation\Rules\NumericVal;
use Respect\Validation\Rules\OneOf;
use Respect\Validation\Rules\Regex;
use Respect\Validation\Rules\Time;
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
        return new NumericVal();
    }

    /**
     * @param int $maxMin
     * @param int $max
     * @return \Respect\Validation\Validatable
     */
    protected static function isText(int $maxMin = 128, int $max = -1): Validatable
    {
        try {
            if ($max <= 0) {
                return new AllOf(
                    new Rules\StringType(),
                    new Length(null, $maxMin)
                );
            }
            return new AllOf(
                new Rules\StringType(),
                new Rules\NotEmpty(),
                new Length($maxMin, $max)
            );
        } catch (ComponentException $e) {
            throw new RuntimeException("impossible d'initialiser " . static::class . " un problème dans " . __METHOD__);
        }
    }

    /**
     * @param int $maxMin
     * @param int $max
     * @return \Respect\Validation\Validatable
     */
    protected static function isAlphaNum(int $maxMin = 128, int $max = -1): Validatable
    {
        try {
            if ($max <= 0) {
                return new OneOf(
                    new Equals(''),
                    new AllOf(
                        new Alnum(),
                        new Length(null, $maxMin)
                    )
                );
            }
            return new AllOf(
                new Alnum(),
                new Rules\NotEmpty(),
                new Length($maxMin, $max)
            );
        } catch (ComponentException $e) {
            throw new RuntimeException("impossible d'initialiser " . static::class . " un problème dans " . __METHOD__);
        }
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isUid(): Validatable
    {
        return new Regex('/[a-fA-F0-9]{8}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{4}-[a-fA-F0-9]{12}/');
    }

    /**
     * valide un slug
     * @param int $minMax
     * @param int|null $max
     * @return \Respect\Validation\Validatable
     */
    protected static function isSlug(int $minMax = 128, ?int $max = null): Validatable
    {
        try {
            $slug = new Regex('/^[a-z0-9]+(?:[._-][a-z0-9]+)*$/i');
            if ($max === null) {
                return new AllOf(
                    $slug,
                    new Length(null, $minMax)
                );
            }
            return new AllOf(
                $slug,
                new Rules\NotEmpty(),
                new Length(max(1, $minMax), $max)
            );
        } catch (ComponentException $e) {
            throw new RuntimeException("impossible d'initialiser " . static::class . " un problème dans " . __METHOD__);
        }
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
        return new Digit('+.-/ ');
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
    protected static function isDateTimeIso(): Validatable
    {
        return new AnyOf(
            new DateTime(DATE_ATOM),
            new DateTime('Y-m-d\TH:i:s')
        );
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isDateTime(): Validatable
    {
        return new AnyOf(
            new DateTime('Y-m-d H:i:s'),
            new DateTime('Y-m-d H:i')
        );
    }

    /**
     * @return \Respect\Validation\Validatable
     */
    protected static function isTime(): Validatable
    {
        return new AnyOf(
            new Time('H:i:s'),
            new Time('H:i')
        );
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
        return new AnyOf(
            new NullType(),
            new Identical(''),
            new Identical(0)
        );
    }

    /**
     * rend une règle de validation nullable
     * @param \Respect\Validation\Validatable $rules
     * @return \Respect\Validation\Validatable
     */
    protected static function isNullable(Validatable $rules): Validatable
    {
        return new AnyOf(
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
        return new AnyOf(
            new NullType(),
            new Identical(''),
            new Identical(0),
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
     */
    protected static function isAssocArray(array $rules): Validatable
    {
        try {
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
        } catch (ComponentException $e) {
            throw new RuntimeException("impossible d'initialiser " . static::class . " un problème dans " . __METHOD__);
        }
    }

    /**
     * @param array<string,\Respect\Validation\Validatable|null> $rules
     * @param string $className
     * @return \Respect\Validation\Validatable
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
    protected static function isOneOf(array $rules): Validatable
    {
        return new OneOf(...$rules);
    }

    /**
     * @param array<\Respect\Validation\Validatable> $rules
     * @return \Respect\Validation\Validatable
     */
    protected static function isAnyOf(array $rules): Validatable
    {
        return new AnyOf(...$rules);
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
     */
    protected static function isMail(int $length = 128): Validatable
    {
        try {
            return new AllOf(
                new Length(null, $length),
                new Rules\Email()
            );
        } catch (ComponentException $e) {
            throw new RuntimeException("impossible d'initialiser " . static::class . " un problème dans " . __METHOD__);
        }
    }

    /**
     * valide une URL
     * @return \Respect\Validation\Validatable
     */
    protected static function isUrl(): Validatable
    {
        return new Rules\Url();
    }

    /**
     * valide un text au format UriData
     * @return \Respect\Validation\Validatable
     */
    protected static function isUriData(): Validatable
    {
        $reg = "/^data:([\w\-\.]+\/[\w\-\.+]+)(;charset=[\w\-\.]+)?(;\w+)?,.*$/";
        return new Rules\Regex($reg);
    }

    /**
     * Valide un mot de passe
     * @param int $length longueur minimum
     * @param int $lower nombre de lettres minuscules
     * @param int $upper nombre de lettres majuscules
     * @param int $number nombre de chiffres
     * @param int $symbol nombre de symboles
     * @param string|null $customSymbols liste des symboles
     * @return \Respect\Validation\Validatable
     */
    protected static function isPassword(
        int     $length,
        int     $lower,
        int     $upper,
        int     $number,
        int     $symbol,
        ?string $customSymbols = null
    ): Validatable {
        try {
            $rules = [
                (new Length($length))
                    ->setName('longueur minimum')
                    ->setTemplate("Le mot de passe doit contenir $length caractères")
            ];
            $undesiredCharRule = (new Callback(static function ($input) use ($customSymbols): bool {
                $customSymbols = $customSymbols !== null ? preg_quote($customSymbols, '/') : '\W_';
                $find = preg_match_all('/([^a-zA-Z\d' . $customSymbols . '])/', $input);
                if ($find === false) {
                    return false;
                }
                return $find === 0;
            }))
                ->setName('caractere non autorisé')
                ->setTemplate("Le mot de passe contient des caractères non autorisé");
            $rules[] = $undesiredCharRule;
            if ($lower > 0) {
                $lowerRule = (new Callback(static function ($input) use ($lower): bool {
                    $find = preg_match_all('/([a-z])/', $input);
                    if ($find === false) {
                        return false;
                    }
                    return $find >= $lower;
                }))
                    ->setName('minuscules nécessaires')
                    ->setTemplate("Le mot de passe doit contenir au moins $lower minuscules");
                $rules[] = $lowerRule;
            }
            if ($upper > 0) {
                $upperRule = (new Callback(static function ($input) use ($upper): bool {
                    $find = preg_match_all('/([A-Z])/', $input);
                    if ($find === false) {
                        return false;
                    }
                    return $find >= $upper;
                }))
                    ->setName('majuscules nécessaires')
                    ->setTemplate("Le mot de passe doit contenir au moins $upper majuscules");
                $rules[] = $upperRule;
            }
            if ($number > 0) {
                $numberRule = (new Callback(static function ($input) use ($number): bool {
                    $find = preg_match_all('/(\d)/', $input);
                    if ($find === false) {
                        return false;
                    }
                    return $find >= $number;
                }))
                    ->setName('chiffres nécessaires')
                    ->setTemplate("Le mot de passe doit contenir au moins $number chiffres");
                $rules[] = $numberRule;
            }
            if ($symbol > 0) {
                $symbolRule = (new Callback(static function ($input) use ($symbol, $customSymbols): bool {
                    $customSymbols = $customSymbols !== null ? preg_quote($customSymbols, '/') : '\W_';
                    $find = preg_match_all('/([' . $customSymbols . '])/', $input);
                    if ($find === false) {
                        return false;
                    }
                    return $find >= $symbol;
                }))
                    ->setName('symboles nécessaires')
                    ->setTemplate("Le mot de passe doit contenir au moins $symbol symboles");
                $rules[] = $symbolRule;
            }
            return (new AllOf(...$rules))
                ->setTemplate('Toutes les règles doivent passer pour {{name}}');
        } catch (ComponentException $e) {
            throw new RuntimeException("impossible d'initialiser " . static::class . " un problème dans " . __METHOD__);
        }
    }

    /**
     * @param array<\Respect\Validation\Validatable> $rules
     * @return \Respect\Validation\Validatable
     */
    protected static function isAllOf(array $rules): Validatable
    {
        return new AllOf(...$rules);
    }
}
