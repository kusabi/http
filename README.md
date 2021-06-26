# HTTP Library

![Tests](https://github.com/kusabi/http/workflows/tests/badge.svg)
[![codecov](https://codecov.io/gh/kusabi/http/branch/main/graph/badge.svg)](https://codecov.io/gh/kusabi/http)
[![Licence Badge](https://img.shields.io/github/license/kusabi/http.svg)](https://img.shields.io/github/license/kusabi/http.svg)
[![Release Badge](https://img.shields.io/github/release/kusabi/http.svg)](https://img.shields.io/github/release/kusabi/http.svg)
[![Tag Badge](https://img.shields.io/github/tag/kusabi/http.svg)](https://img.shields.io/github/tag/kusabi/http.svg)
[![Issues Badge](https://img.shields.io/github/issues/kusabi/http.svg)](https://img.shields.io/github/issues/kusabi/http.svg)
[![Code Size](https://img.shields.io/github/languages/code-size/kusabi/http.svg?label=size)](https://img.shields.io/github/languages/code-size/kusabi/http.svg)

<sup>Yet another request library, built for PHP</sup>

## Compatibility and dependencies

This library is compatible with PHP version `7.2`, `7.3`, `7.4` and `8.0`.

This library depends on `psr/http-client` to implement a [PSR-18][] client


# Installation

Installation is simple using composer.

```bash
composer require kusabi/http
```

Or simply add it to your `composer.json` file

```json
{
    "require": {
        "kusabi/http": "^1.0"
    }
}
```

[PSR-18]: https://www.php-fig.org/psr/psr-18/