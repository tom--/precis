# International username and password handling with PRECIS Framework

### Summary

[PRECIS](https://tools.ietf.org/html/rfc7564),
"Preparation, Enforcement, and Comparison of Internationalized Strings", is a
framework for handling Unicode strings representing thinks like network addresses,
[identifiers and passwords](http://tools.ietf.org/html/rfc7613).

The client (e.g. web browser) **prepares** strings (e.g. username, password) before
sending them over the network. Servers (e.g. a PHP app)
**enforce** specific rules
(e.g. checking for disallowed characters, case folding, etc.)
on strings *before* comparing them (e.g. with values in a database).
Both strings involved in a comparison must be enforced. If a hash function is used in the
comparison (e.g. for a password), enforcement comes before hashing and comparison.

PRECIS standardizes such preparation and enforcement (obsoleting older standards)
and this package provides a PHP implementation.

### Functionality

The `Precis` class provides static methods to:

- test strings against the strings classes defined in [PRECIS Framework RFC 7564](https://tools.ietf.org/html/rfc7564)
    - IdentifierClass
    - FreeformClass

- prepare and enforce strings according to the profiles
    - UsernameCaseMapped [RFC7613, Section 3.2](http://tools.ietf.org/html/rfc7613)
    - UsernameCasePreserved [RFC7613, Section 3.3](http://tools.ietf.org/html/rfc7613)
    - OpaqueString (used for passwords etc.) [RFC7613, Section 4.2](http://tools.ietf.org/html/rfc7613)
    - Nickname [RFC-ietf-precis-nickname-19](https://datatracker.ietf.org/doc/draft-ietf-precis-nickname/)

In order to implement these, certain Unicode functionality is required that was
absent in PHP before PHP 7.0 introduced [IntlChar](http://php.net/manual/en/class.intlchar.php).
The package has two more classes as a stop-gap. (They may be useful for other purposes.)

- `CaseFold` provides static methods for:

    - Unicode [case folding](http://www.unicode.org/reports/tr44/#CaseFolding.txt)
    (which is [different from the case conversion](http://unicode.org/faq/casemap_charprop.html) provided by `mb_convert_case()`)

- `Bidi` provides static methods for:

    - Getting the Unicode [Bidi](http://unicode.org/faq/bidi.html) [property](http://www.unicode.org/reports/tr9/) for a character
    - Applying the [IDNA Bidi Rule](https://tools.ietf.org/html/rfc5893)



### Method index

All public methods are static and documented in PHP dock blocks:

```php
// test for PRECIS string classes
Precis::isIdentifier()
Precis::isFreeform()

// prepare for PRECIS profiles
Precis::prepareUsernameCaseMapped()
Precis::prepareUsernameCasePreserved()
Precis::prepareOpaqueString()
Precis::prepareNickname()

// enforce PRECIS profiles
Precis::enforceUsernameCaseMapped()
Precis::enforceUsernameCasePreserved()
Precis::enforceOpaqueString()
Precis::enforceNickname()

// PRECIS utilities
Precis::analyzeString()
Precis::getStringClass()
Precis::getPrecisProperty()
Precis::getHasCompat()
Precis::getContextO()
Precis::getContextJ()
Precis::mapFullwidthHalfwidthToCompat()

// general UTF-8 character utilities
Precis::utf8chr()
Precis::utf8ord()
Precis::codePoint2utf8()
Precis::utf82CodePoint()

// Unicode case folding
CaseFold::fold()

// Unicode Bidi class and IDNA Bidi rule
Bidi::getClass()
Bidi::rule()
```

### Copyright and license

- Copyright (c) 2015 Spinitron LLC
- ISC license https://opensource.org/licenses/ISC

