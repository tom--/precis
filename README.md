# International username and password strings using PRECIS

### Summary

PRECIS, "Preparation, Enforcement, and Comparison of Internationalized Strings", is a
framework for handling Unicode strings representing thinks like network addresses, 
identifiers and passwords.

The client (e.g. web browser) **prepares** strings (e.g. username and passsword) before 
sending them over the network. Servers (e.g. a PHP web app) **enforce** specific rules
(e.g. checking for disallowed characters, case folding, trimming, Unicode normalization) 
on the recieved strings *before* comparing them with local data (e.g. usernames and 
passwords in the database). The server must also prepare the local strings either
at lookup time or when they are stored (for hashed passwords it must be the latter). 

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
The package has two more classes as a stop-gap. They may be useful for other purposes.

- `CaseFold` provides static methods for:

    - Unicode [casefolding](http://www.unicode.org/reports/tr44/#CaseFolding.txt) 
    (which is [different from the case conversion](http://unicode.org/faq/casemap_charprop.html) provided by `mb_convert_case()`)

- `Bidi` provides static methods for:

    - Getting the Unicode [Bidi](http://unicode.org/faq/bidi.html) [property](http://www.unicode.org/reports/tr9/) for a character
    - Applying the [IDNA Bidi Rule](https://tools.ietf.org/html/rfc5893)

### Methods

All public methods are static and documented in PHP dock blocks:

```php
Precis::isIdentifier()
Precis::isFreeform()

Precis::prepareUsernameCaseMapped()
Precis::prepareUsernameCasePreserved()
Precis::prepareOpaqueString()
Precis::prepareNickname()

Precis::enforceUsernameCaseMapped()
Precis::enforceUsernameCasePreserved()
Precis::enforceOpaqueString()
Precis::enforceNickname()

Precis::hex2utf8()
Precis::utf8chr()
Precis::utf8ord()
Precis::utf8CodePoint()

Precis::analyzeString()
Precis::getStringClass()
Precis::getPrecisProperty()
Precis::getHasCompat()
Precis::getContextO()
Precis::getContextJ()
Precis::mapFullwidthHalfwidthToCompat()

CaseFold::fold()

Bidi::getClass()
Bidi::rule()
```

### Copyright and license

- Copyright (c) 2015 Spinitron LLC
- ISC license https://opensource.org/licenses/ISC

