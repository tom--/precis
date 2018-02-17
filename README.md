# International username, password, and nickname handling with PRECIS Framework

### Summary

[PRECIS](https://tools.ietf.org/html/rfc8264),
"Preparation, Enforcement, and Comparison of Internationalized Strings", is a
framework for handling Unicode strings representing things like network addresses,
[identifiers and passwords](https://tools.ietf.org/html/rfc8265), and [nicknames](https://tools.ietf.org/html/rfc8266).

In PRECIS terms, a client (e.g. web browser) **prepares** a string (e.g. username, password) before
sending it over the network. A server (e.g. a PHP web app)
**enforces** specific rules
(e.g. checking for disallowed characters, case mapping, etc.)
on the string *before* accepting and using it. Enforcement involves an accept/reject decision and may involve changing the string using a PRECIS profile-specific kind of normalization. When comparing two strings, both are enforced before comparion. If a hash function is used in the comparison (e.g. for a password), enforcement comes before hashing and comparison.

The PRECIS RFCs standardize such preparation and enforcement (obsoleting older standards)
and this package provides a PHP implementation.

### Functionality

The `Precis` class provides static methods to:

- test strings against the strings classes defined in [PRECIS Framework RFC 8264](https://tools.ietf.org/html/rfc8264)
    - IdentifierClass
    - FreeformClass

- prepare and enforce strings according to the profiles
    - UsernameCaseMapped [RFC 8265 Section 3.3](https://tools.ietf.org/html/rfc8265#section-3.3)
    - UsernameCasePreserved [RFC 8265 Section 3.4](https://tools.ietf.org/html/rfc8265#section-3.4)
    - OpaqueString (used for passwords etc.) [RFC 8265 Section 4.2](http://tools.ietf.org/html/rfc7613#section-4.2)
    - Nickname [RFC 8266](https://tools.ietf.org/html/rfc8266)

In order to implement these, certain Unicode functionality is required that was
absent in PHP before PHP 7.0 introduced [IntlChar](http://php.net/manual/en/class.intlchar.php).
The package has another class as a stop-gap.

- `Bidi` provides static methods for:

    - Getting the Unicode [Bidi](http://unicode.org/faq/bidi.html) [property](http://www.unicode.org/reports/tr9/) for a character
    - Applying the [IDNA Bidi Rule](https://tools.ietf.org/html/rfc5893)

(Note: The `CaseFold` class in v0.1.1 of this package was removed after PRECIS removed it in revised RFCs.)

### Methods

All public methods are static.

```php
// test for PRECIS string classes
public static function isIdentifier(string $string): bool
public static function isFreeform(string $string): bool

// prepare for PRECIS profiles
public static function prepareUsernameCaseMapped(string $string): string|bool
public static function prepareUsernameCasePreserved(string $string): string|bool
public static function prepareOpaqueString(string $string): string|bool
public static function prepareNickname(string $string): string|bool

// enforce PRECIS profiles
public static function enforceUsernameCaseMapped(string $string): string|bool
public static function enforceUsernameCasePreserved(string $string): string|bool
public static function enforceOpaqueString(string $string): string|bool
public static function enforceNickname(string $string): string|bool
```

This package also has some general Unicode utilities that were required before PHP 7 introduced the `IntlChar` class. These remain as we maintain PHP 5.4 compat for now. And we contunue to use mbstring for the Unicode `toLowerCase()` operation.


```php
// general UTF-8 character utilities
public static function utf8chr(int $ord): string|null
public static function utf8ord(string $string, int $pos = 0): int
public static function codePoint2utf8(string $codePoint): string|null|false
public static function utf82CodePoint(string $string, int $pos = 0, string $style = 'U+'): string

// Unicode Bidi class and IDNA Bidi rule
Bidi::getClass(string $char): string 
public static function rule(string $string): bool
```

### Data

The `Bidi` class has its own "data trait" that contains
the relevant data from the Unicode Character Database.
They are PHP classes in order to exploit opcode caches.
They are traits not because I plan
to reuse them elsewhere but for convenience. First, because they are automatically
generated from the UCD and, second, because it's easier to work on the corresponding
methods without the big array literals in the same editor.

The generators for the data traits are in the `data` directory. They download the
relevant UCD files and write the PHP trait classes to standard output. So you can
regenerate them, for example, as follows (assuming php is in your path):

    cd path/to/precis/project/dir
    php data/generateBidi.php > BidiDataTrait.php
    php data/generateCaseFold.php > CaseFoldDataTrait.php


### Unit tests

The `Precis` tests are incomplete because I could not find test vectors for PRECIS string
classes or profiles or for the Bidi Rule.

The following are relatively well tested:

- UTF-8 utility functions are tested on every Unicode code point
- `Bidi::getClass()`. NOTE: this test downloads UnicideData.txt from UCD (1.5 MB)
- The CONTEXTO and CONTEXTJ rules
- `Precis::getPrecisProperty()`

The `DerivedPropertyValueTest` uses the table of derived property values from [IANA](https://www.iana.org/assignments/precis-tables-6.3.0/precis-tables-6.3.0.xhtml) that's [stuck on Unicode 6.3.0](https://tools.ietf.org/html/rfc8264#section-11.1). Hence we ignore tests where the IANA table says UNASSIGNED and Precis says something else.


### Copyright and license

- Copyright (c) 2015-2018 Spinitron LLC
- ISC license https://opensource.org/licenses/ISC
