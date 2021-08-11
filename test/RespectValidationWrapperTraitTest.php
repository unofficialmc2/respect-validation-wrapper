<?php
declare(strict_types=1);

namespace Respect\Validaton\Wrapper\Test;

use Respect\Validaton\Wrapper\RespectValidationWrapperTrait;
use RuntimeException;

/**
 * Class ValidatorBaseStub
 * @method  isBool()
 * @method  isTrue()
 * @method  isFalse()
 * @method  isTelNum()
 * @method  isDate()
 * @method  isDateTime()
 * @method  isTime()
 * @method  isNullable($r)
 * @method  isNullableOrEmpty($r)
 * @method  isArray($r = null)
 * @method  isAssocArray($r)
 * @method  isObject($r, $c = null)
 * @method  isAllOf($r)
 * @method  isOneOf($r)
 * @method  isId()
 * @method  isNumeric()
 * @method  isText($m = 0, $n = 0)
 * @method  isAlphaNum($m = 0, $n = 0)
 * @method  isUid()
 * @method  not($r)
 * @method  is($e)
 * @method  isIn($a)
 * @method  isNull()
 * @method  isNullOrEmpty()
 * @method  isMail($l = 128)
 * @method  isUrl()
 * @package Respect\Validato\Wrapper\Test
 */
class ValidatorBaseStub
{
    use RespectValidationWrapperTrait;

    /**
     * @param string $name
     * @param mixed $arguments
     * @return mixed
     */
    public function __call(string $name, $arguments)
    {
        if (!method_exists(RespectValidationWrapperTrait::class, $name)) {
            throw new RuntimeException("la methode $name n'existe pas");
        }
        return call_user_func_array([self::class, $name], $arguments);
    }
}

/**
 * Test de RespectValidationWrapperTrait
 * @package Test\Validator
 */
class RespectValidationWrapperTraitTest extends TestCase
{

    /**
     * test de IsId
     */
    public function testIsId(): void
    {
        $v = (new ValidatorBaseStub)->isId();
        self::assertTrue($v->validate(1));
        self::assertTrue($v->validate("1"));
        self::assertFalse($v->validate(0));
        self::assertFalse($v->validate(-1));
        self::assertFalse($v->validate("-1"));
    }

    /**
     * test de IsNumeric
     */
    public function testIsNumeric(): void
    {
        $v = (new ValidatorBaseStub)->isNumeric();
        self::assertTrue($v->validate(1));
        self::assertTrue($v->validate('1'));
        self::assertTrue($v->validate(0));
        self::assertTrue($v->validate(-1));
        self::assertTrue($v->validate('-1'));
        self::assertFalse($v->validate('a'));
    }

    /**
     * test de IsText
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public function testIsText(): void
    {
        $v = (new ValidatorBaseStub)->isText();
        self::assertTrue($v->validate('aaaaaaaaaaaaaaaaa'));
        self::assertFalse($v->validate(100000004));
        $v = (new ValidatorBaseStub)->isText(5);
        self::assertTrue($v->validate('aaaa'));
        self::assertFalse($v->validate('aaaaaaaaaaaaaaaaa'));
        $v = (new ValidatorBaseStub)->isText(2, 5);
        self::assertFalse($v->validate('a'));
        self::assertTrue($v->validate('aaaa'));
        self::assertFalse($v->validate('aaaaaaa'));
    }

    /**
     * test de tIsAlphaNum
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public function testIsAlphaNum(): void
    {
        $v = (new ValidatorBaseStub)->isAlphaNum();
        self::assertTrue($v->validate('aaaa123'));
        self::assertFalse($v->validate('tu est bouché?'));
    }

    /**
     * test de IsUid
     */
    public function testIsUid(): void
    {
        $v = (new ValidatorBaseStub)->isUid();
        self::assertTrue($v->validate('ea015e4e-087c-4183-a6fa-22ad54e4a270'));
        self::assertFalse($v->validate(uniqid('', true)));
    }

    /**
     * test de IsBool
     */
    public function testIsBool(): void
    {
        $v = (new ValidatorBaseStub)->isBool();
        self::assertTrue($v->validate(true));
        self::assertTrue($v->validate(false));
        self::assertFalse($v->validate(0));
        self::assertFalse($v->validate(1));
        self::assertFalse($v->validate('a'));
    }

    /**
     * test de IsTrue
     */
    public function testIsTrue(): void
    {
        $v = (new ValidatorBaseStub)->isTrue();
        self::assertTrue($v->validate(true));
        self::assertFalse($v->validate(false));
    }

    /**
     * test de IsFalse
     */
    public function testIsFalse(): void
    {
        $v = (new ValidatorBaseStub)->isFalse();
        self::assertTrue($v->validate(false));
        self::assertFalse($v->validate(true));
    }

    /**
     * test de IsTelNum
     */
    public function testIsTelNum(): void
    {
        $v = (new ValidatorBaseStub)->isTelNum();
        self::assertTrue($v->validate('0612345678'));
        self::assertTrue($v->validate('06 12 34 56 78'));
        self::assertTrue($v->validate('06 12 345 678'));
        self::assertTrue($v->validate('+336 12 345 678'));
//        self::assertFalse($v->validate('ligne rouge'));
//        self::assertFalse($v->validate('0612345xxx'));
    }

    /**
     * test de IsDate
     */
    public function testIsDate(): void
    {
        $v = (new ValidatorBaseStub)->isDate();
        self::assertTrue($v->validate('2020-01-01'));
        self::assertFalse($v->validate('1er janvier 2020'));
    }

    /**
     * test de IsDateTime
     */
    public function testIsDateTime(): void
    {
        $v = (new ValidatorBaseStub)->isDateTime();
        self::assertTrue($v->validate('2020-01-01 12:36:26'));
        self::assertFalse($v->validate('1er janvier 2020 6h3m10s'));
        self::assertFalse($v->validate('1er janvier 2020 12:36:26'));
        self::assertFalse($v->validate('2020-01-01 6h3m10s'));
    }

    /**
     * test de IsTime
     */
    public function testIsTime(): void
    {
        $v = (new ValidatorBaseStub)->isTime();
        self::assertTrue($v->validate('15:52:30'));
        self::assertFalse($v->validate('15:52'));
        self::assertFalse($v->validate('15h5210s'));
    }

    /**
     * test de IsNullable
     */
    public function testIsNullable(): void
    {
        $v = (new ValidatorBaseStub)->isNullable((new ValidatorBaseStub)->isTrue());
        self::assertTrue($v->validate(null));
        self::assertTrue($v->validate(true));
        self::assertFalse($v->validate(false));
        self::assertFalse($v->validate('aze'));
    }

    /**
     * test de IsNullableOrEmpty
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public function testIsNullableOrEmpty(): void
    {
        $v = (new ValidatorBaseStub)->isNullableOrEmpty((new ValidatorBaseStub)->isText());
        self::assertTrue($v->validate(null));
        self::assertTrue($v->validate(''));
        self::assertTrue($v->validate(0));
        self::assertTrue($v->validate('aze ert ertert, 3546584'));
        self::assertFalse($v->validate(12354));
    }

    /**
     * test de IsArray
     */
    public function testIsArray(): void
    {
        $v = (new ValidatorBaseStub)->isArray();
        self::assertTrue($v->validate(['a']));
        self::assertTrue($v->validate([]));
        self::assertFalse($v->validate('a'));
        $v = (new ValidatorBaseStub)->isArray((new ValidatorBaseStub)->isTrue());
        self::assertTrue($v->validate([true]));
        self::assertTrue($v->validate([]));
        self::assertFalse($v->validate(true));
        self::assertFalse($v->validate([false]));
    }

    /**
     * test de IsAssocArray
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public function testIsAssocArray(): void
    {
        $v = (new ValidatorBaseStub)->isAssocArray([
            'id' => (new ValidatorBaseStub)->isId(),
            'libelle?' => (new ValidatorBaseStub)->isText()
        ]);
        self::assertTrue($v->validate([
            'id' => 1,
            'libelle' => 'aze'
        ]));
        self::assertTrue($v->validate([
            'id' => 2
        ]));
        self::assertFalse($v->validate([]));
        self::assertFalse($v->validate([
            'id' => 'aze',
            'libelle' => 'aze'
        ]));
    }

    /**
     * test de IsObject
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public function testIsObject(): void
    {
        $v = (new ValidatorBaseStub)->isObject([
            'id' => (new ValidatorBaseStub)->isId(),
            'libelle?' => (new ValidatorBaseStub)->isText()
        ]);
        self::assertTrue($v->validate((object)[
            'id' => 1,
            'libelle' => 'aze'
        ]));
        self::assertTrue($v->validate((object)[
            'id' => 2
        ]));
        self::assertFalse($v->validate([]));
        self::assertFalse($v->validate((object)[]));
        self::assertFalse($v->validate((object)[
            'id' => 'aze',
            'libelle' => 'aze'
        ]));
        self::assertFalse($v->validate([
            'id' => 1,
            'libelle' => 'aze'
        ]));
    }

    /**
     * test de IsAllOf
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public function testIsAllOf(): void
    {
        $v = (new ValidatorBaseStub)->isAllOf([
            (new ValidatorBaseStub)->isArray(),
            (new ValidatorBaseStub)->isAssocArray([
                'list' => (new ValidatorBaseStub)->isArray()
            ])
        ]);
        self::assertTrue($v->validate([
            'list' => []
        ]));
        self::assertFalse($v->validate(true));
    }

    /**
     * test de IsOneOf
     */
    public function testIsOneOf(): void
    {
        $v = (new ValidatorBaseStub)->isOneOf([
            (new ValidatorBaseStub)->isTrue(),
            (new ValidatorBaseStub)->isFalse(),
        ]);
        self::assertTrue($v->validate(false));
        self::assertTrue($v->validate(true));
        self::assertFalse($v->validate('aze'));
    }

    /**
     * test de not
     */
    public function testNot(): void
    {
        $v = (new ValidatorBaseStub)->not((new ValidatorBaseStub)->isTrue());
        self::assertTrue($v->validate(false));
        self::assertFalse($v->validate(true));
        $v = (new ValidatorBaseStub)->not((new ValidatorBaseStub)->isId());
        self::assertTrue($v->validate('a'));
    }

    /**
     * est de is
     */
    public function testIs()
    {
        $v = (new ValidatorBaseStub)->is(123);
        self::assertTrue($v->validate(123));
        self::assertFalse($v->validate('a'));
        $v = (new ValidatorBaseStub)->is('a');
        self::assertTrue($v->validate('a'));
        self::assertFalse($v->validate(123));
    }

    /**
     * est de isIn
     */
    public function testIsIn()
    {
        $v = (new ValidatorBaseStub)->isIn(['a', 'e', 'i']);
        self::assertTrue($v->validate('a'));
        self::assertFalse($v->validate('b'));
        self::assertFalse($v->validate('u'));
    }

    /**
     * test de isNull
     */
    public function testIsNull(): void
    {
        $v = (new ValidatorBaseStub)->isNull();
        self::assertTrue($v->validate(null));
        self::assertFalse($v->validate(0));
        self::assertFalse($v->validate(''));
        self::assertFalse($v->validate('123'));
        self::assertFalse($v->validate(12345));
    }

    /**
     * test de isNullOrEmpty
     */
    public function testIsNullOrEmpty(): void
    {
        $v = (new ValidatorBaseStub)->isNullOrEmpty();
        self::assertTrue($v->validate(null));
        self::assertTrue($v->validate(0));
        self::assertTrue($v->validate(''));
        self::assertFalse($v->validate('123'));
        self::assertFalse($v->validate(12345));
    }


    /**
     * test de isMail
     * @throws \Respect\Validation\Exceptions\ComponentException
     */
    public function testIsMail(): void
    {
        $v = (new ValidatorBaseStub)->isMail();
        self::assertTrue($v->validate('adresse@mail.net'));
        self::assertFalse($v->validate('mail.net'));
        self::assertTrue($v->validate('adresse+test@mail.net'));
        self::assertTrue($v->validate('adresse.test@mail.net'));
    }

    /**
     * test de isUrl
     */
    public function testIsUrl(): void
    {
        $v = (new ValidatorBaseStub)->isUrl();
        self::assertTrue($v->validate('http://www.exemple.net'));
        self::assertFals($v->validate('http:exemple.net'));
    }
}
