<?php
declare(strict_types=1);

namespace Respect\Validaton\Wrapper\Test;

use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validatable;
use Respect\Validaton\Wrapper\RespectValidationWrapperTrait;
use RuntimeException;

/**
 * Class ValidatorBaseStub
 * @method Validatable isBool()
 * @method Validatable isTrue()
 * @method Validatable isFalse()
 * @method Validatable isDate()
 * @method Validatable isDateTime()
 * @method Validatable isDateTimeIso()
 * @method Validatable isTime()
 * @method Validatable isNullable(Validatable $r)
 * @method Validatable isNullableOrEmpty(Validatable $r)
 * @method Validatable isArray(Validatable $r = null)
 * @method Validatable isAssocArray(array $r)
 * @method Validatable isObject(array $r, string $c = null)
 * @method Validatable isAllOf(array $r)
 * @method Validatable isOneOf(array $r)
 * @method Validatable isAnyOf(array $r)
 * @method Validatable isId()
 * @method Validatable isNumeric()
 * @method Validatable isText(int $m = 0, int $n = 0)
 * @method Validatable isAlphaNum(int $m = 0, int $n = 0)
 * @method Validatable isUid()
 * @method Validatable not(Validatable $r)
 * @method Validatable is($e)
 * @method Validatable isIn(array $a)
 * @method Validatable isNull()
 * @method Validatable isNullOrEmpty()
 * @method Validatable isMail(int $l = 128)
 * @method Validatable isUrl()
 * @method Validatable isUriData()
 * @method Validatable isSlug(int $m = 0, int $n = 0)
 * @method Validatable isPassword(int $k, int $l, int $u, int $n, int $s, string $c = null)
 * @method Validatable isMatchingRegex(string $r, int $m = 0, int $n = 0)
 * @method Validatable isPhoneNumber()
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
     */
    public function testIsText(): void
    {
        $v = (new ValidatorBaseStub)->isText();
        self::assertTrue($v->validate('aaaaaaaaaaaaaaaaa'));
        self::assertFalse($v->validate(100000004));
        self::assertFalse($v->validate(str_repeat("a", 129)), 'no config 129 caracteres');
        $v = (new ValidatorBaseStub)->isText(5);
        self::assertTrue($v->validate(''));
        self::assertTrue($v->validate('aaaa'));
        self::assertFalse($v->validate('aaaaaaaaaaaaaaaaa'));
        $v = (new ValidatorBaseStub)->isText(2, 5);
        self::assertFalse($v->validate(''));
        self::assertFalse($v->validate('a'));
        self::assertTrue($v->validate('aaaa'));
        self::assertFalse($v->validate('aaaaaaa'));
    }

    /**
     * test de tIsAlphaNum
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
     * test de IsDate
     */
    public function testIsDate(): void
    {
        $v = (new ValidatorBaseStub)->isDate();
        self::assertTrue($v->validate('2020-01-01'));
        self::assertFalse($v->validate('1er janvier 2020'));
    }

    /**
     * test de IsDateTimeISO
     * Prise en charge des date time avec la norme ISO
     */
    public function testIsDateTimeIso(): void
    {
        $v = (new ValidatorBaseStub)->isDateTimeIso();
        self::assertTrue($v->validate('2003-02-01T12:34:56+02:00'), "ISO");
        self::assertTrue($v->validate('2020-01-01T12:36:26'), "ISO UTC");
        self::assertFalse($v->validate('2020-01-01 12:36:26'), "ISO simplifié local");
        self::assertFalse($v->validate('2020-01-01 12:36'), "ISO simplifié local sans minutes");
        self::assertFalse($v->validate('1er janvier 2020 6h3m10s'));
        self::assertFalse($v->validate('1er janvier 2020 12:36:26'));
        self::assertFalse($v->validate('2020-01-01 6h3m10s'));
    }

    /**
     * test de IsDateTime
     */
    public function testIsDateTime(): void
    {
        $v = (new ValidatorBaseStub)->isDateTime();
        self::assertFalse($v->validate('2020-01-01T12:36:26+02:00'), "ISO");
        self::assertFalse($v->validate('2020-01-01T12:36:26'), "ISO UTC");
        self::assertTrue($v->validate('2020-01-01 12:36:26'), "ISO simplifié local");
        self::assertTrue($v->validate('2020-01-01 12:36'), "ISO simplifié local sans minutes");
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
        self::assertTrue($v->validate('15:52:30'), "heure minute seconde");
        self::assertTrue($v->validate('15:52'), "heure minute");
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
     * test de IsAssocArray
     */
    public function testIsAssocArrayWithoutKey(): void
    {
        $v = (new ValidatorBaseStub)->isAssocArray([
            'id' => (new ValidatorBaseStub)->isId(),
            '-ref' => null
        ]);
        self::assertTrue($v->validate([
            'id' => 1
        ]));
        self::assertFalse($v->validate([
            'id' => 1,
            'ref' => 'AZERTY123456'
        ]));
    }

    /**
     * test de IsObject
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
     * test de IsObject
     */
    public function testIsObjectWithout(): void
    {
        $v = (new ValidatorBaseStub)->isObject([
            'id' => (new ValidatorBaseStub)->isId(),
            '-ref' => null
        ]);
        self::assertTrue($v->validate((object)[
            'id' => 2
        ]));
        self::assertFalse($v->validate((object)[
            'id' => 2,
            'ref' => 'AZEERTY123456'
        ]));
    }

    /**
     * test de IsAllOf
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
    public function testIs(): void
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
    public function testIsIn(): void
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
        /** @noinspection HttpUrlsUsage */
        self::assertTrue($v->validate('http://www.exemple.net'));
        self::assertFalse($v->validate('http:exemple.net'));
    }

    /**
     * test la validation dun Uri Data
     */
    public function testUriData(): void
    {
        $data = "data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAoAAAADCAMAAACd425HAAAABGdBTUEAALGPC/xhBQAAACBjSFJNAAB6"
            . "JgAAgIQAAPoAAACA6AAAdTAAAOpgAAA6mAAAF3CculE8AAAAXVBMVEVChfRBhfV3cLjvPzD2mhL4vQhUiOg5lqlqiU34OzJChfQ9h/qO"
            . "Z53tQjH2khXtuhdjjsw7jtCjaUjvQDRChfQ/hvefYYvsQDP3mRLyuxFKh+s/ieegakzuQTT///84b11YAAAAHnRSTlNnUigmHiQgREkn"
            . "YFF3a3ZtcoaUdyQzHjAmLmByLy6csil2AAAAAWJLR0QecgogKwAAAAd0SU1FB+UJFA4DLqVT8S4AAAApSURBVAjXY2BgZGJmYWVj5+Bk"
            . "4OLm4eXjFxAUEmYQERUTl5CUkpaRBQASmwG0/WmHKAAAACt0RVh0Q29tbWVudABSZXNpemVkIG9uIGh0dHBzOi8vZXpnaWYuY29tL3Jl"
            . "c2l6ZUJpjS0AAAAldEVYdGRhdGU6Y3JlYXRlADIwMjEtMDktMjBUMTQ6MDM6MzgrMDA6MDAaKnZPAAAAJXRFWHRkYXRlOm1vZGlmeQAy"
            . "MDIxLTA5LTIwVDE0OjAzOjM4KzAwOjAwa3fO8wAAABJ0RVh0U29mdHdhcmUAZXpnaWYuY29toMOzWAAAAABJRU5ErkJggg==";
        $v = (new ValidatorBaseStub)->isUriData();
        self::assertTrue($v->validate($data));
        self::assertFalse($v->validate('http:exemple.net'));
    }

    /**
     * test de la validation d'un slug
     */
    public function testSlug(): void
    {
        $v = (new ValidatorBaseStub)->isSlug();
        self::assertTrue($v->validate('az09'));
        self::assertTrue($v->validate('az09.az09'));
        self::assertTrue($v->validate('az09.az09.az09'));
        self::assertTrue($v->validate('az09-az09'));
        self::assertTrue($v->validate('az09-az09-az09'));
        self::assertTrue($v->validate('az09_az09'));
        self::assertTrue($v->validate('az09_az09_az09'));
        self::assertTrue($v->validate('az09.az09-az09_az09'));
        self::assertFalse($v->validate('http:exemple.net'));
        $v = (new ValidatorBaseStub)->isSlug(6);
        self::assertTrue($v->validate('az09'), "bonne longueur");
        self::assertFalse($v->validate('az09az09'), "trop long");
        $v = (new ValidatorBaseStub)->isSlug(6, 10);
        self::assertFalse($v->validate('az09'), "trop court");
        self::assertTrue($v->validate('az09az09'), "bonne longueur");
        self::assertFalse($v->validate('az09az09az09'), "trop long");
    }

    public function testPassword(): void
    {
        // test longueur de 4 caractères
        $v = (new ValidatorBaseStub)->isPassword(4, 0, 0, 0, 0);
        self::assertFalse($v->validate('aze'));
        self::assertTrue($v->validate('AzertY'));
        // test 2 minuscules
        $v = (new ValidatorBaseStub)->isPassword(0, 2, 0, 0, 0);
        self::assertFalse($v->validate('AAa'));
        self::assertTrue($v->validate('Aaa'));
        // test 2 majuscules
        $v = (new ValidatorBaseStub)->isPassword(0, 0, 2, 0, 0);
        self::assertFalse($v->validate('aaA'));
        self::assertTrue($v->validate('aAA'));
        // test 2 chiffres
        $v = (new ValidatorBaseStub)->isPassword(0, 0, 0, 2, 0);
        self::assertFalse($v->validate('aaA1'));
        self::assertTrue($v->validate('aAA12'));
        // test 2 symboles
        $v = (new ValidatorBaseStub)->isPassword(0, 0, 0, 0, 2);
        self::assertFalse($v->validate('aaA1'));
        self::assertTrue($v->validate('aA/A?12'));
        // test avec des règles complètes
        $v = (new ValidatorBaseStub)->isPassword(8, 2, 2, 2, 1);
        self::assertFalse($v->validate('mot de passe'));
        self::assertFalse($v->validate('motdepasse'));
        self::assertFalse($v->validate('MotDePasse'));
        self::assertFalse($v->validate('M0t2Passe'));
        self::assertTrue($v->validate('M0t2P@sse'));
        // test de récuperation des erreur
        try {
            $v->assert('motdepasse');
        } catch (NestedValidationException $e) {
            $message = "- Toutes les règles doivent passer pour \"motdepasse\"" . PHP_EOL
                . "  - Le mot de passe doit contenir au moins 2 majuscules" . PHP_EOL
                . "  - Le mot de passe doit contenir au moins 2 chiffres" . PHP_EOL
                . "  - Le mot de passe doit contenir au moins 1 symboles";
            self::assertEquals($message, $e->getFullMessage());
        }
        // test sans symbol custom
        $v = (new ValidatorBaseStub)->isPassword(8, 0, 0, 0, 0);
        self::assertTrue($v->validate('mot de passe'));
        self::assertTrue($v->validate('mot_de_passe'));
        // test avec symbol custom
        $v = (new ValidatorBaseStub)->isPassword(8, 0, 0, 0, 0, '.-_');
        self::assertFalse($v->validate('mot de passe'));
        self::assertTrue($v->validate('mot_de_passe'));
    }

    /**
     * Test de isAnyOf Rule
     */
    public function testIsAnyOf(): void
    {
        $v = (new ValidatorBaseStub)->isAnyOf([
            (new ValidatorBaseStub)->isId(),
            (new ValidatorBaseStub)->isText(),
        ]);
        self::assertTrue($v->validate(1));
        self::assertTrue($v->validate('1'));
        self::assertTrue($v->validate('a'));
        self::assertFalse($v->validate((object)[]));
    }


    /**
     * Test de isMatchingRegex Rule
     */
    public function testIsMatchingRegex(): void
    {
        $v = (new ValidatorBaseStub)->isMatchingRegex('/^[abc]+$/',2);
        self::assertTrue($v->validate('a'));
        self::assertTrue($v->validate('ac'));
        self::assertFalse($v->validate('abc'));
        self::assertFalse($v->validate((object)[]));
        self::assertFalse($v->validate(1));
    }


    /**
     * Test de isPhoneNumber Rule
     */
    public function testIsPhoneNumber(): void
    {
        $v = (new ValidatorBaseStub)->isPhoneNumber();
        self::assertTrue($v->validate('0612345678'));
        self::assertFalse($v->validate('1612345678'));
        self::assertTrue($v->validate('+333.51.51.51.51'));
        self::assertTrue($v->validate('0033351 515 151'));
        // Test issus de deprecated isTelNum
        self::assertTrue($v->validate('0612345678'));
        self::assertTrue($v->validate('06 12 34 56 78'));
        self::assertTrue($v->validate('06 12 345 678'));
        self::assertTrue($v->validate('+336 12 345 678'));
        self::assertFalse($v->validate('ligne rouge'));
        self::assertFalse($v->validate('0612345xxx'));
        self::assertFalse($v->validate('+333.51.51.51'));
        self::assertFalse($v->validate('06 12 34 56'));
    }


}
