# International username and password handling with PRECIS Framework

### Summary

[PRECIS](https://tools.ietf.org/html/rfc7564),
"Preparation, Enforcement, and Comparison of Internationalized Strings", is a
framework for handling Unicode strings representing things like network addresses,
[identifiers and passwords](http://tools.ietf.org/html/rfc7613).

The client (e.g. web browser) **prepares** strings (e.g. username, password) before
sending them over the network. Servers (e.g. a PHP app)
**enforce** specific rules
(e.g. checking for disallowed characters, case mapping, etc.)
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
The package has another class as a stop-gap.

- `Bidi` provides static methods for:

    - Getting the Unicode [Bidi](http://unicode.org/faq/bidi.html) [property](http://www.unicode.org/reports/tr9/) for a character
    - Applying the [IDNA Bidi Rule](https://tools.ietf.org/html/rfc5893)

(Note: The `CaseFold` was removed afterPRECIS stopped using it.)

### Method index

All public methods are static. [Documentation](http://tom--.github.io/precis/namespaces/spinitron.precis.html)

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

// Unicode Bidi class and IDNA Bidi rule
Bidi::getClass()
Bidi::rule()
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


### Copyright and license

- Copyright (c) 2015-2018 Spinitron LLC
- ISC license https://opensource.org/licenses/ISC

