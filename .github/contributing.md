# Coding Standards

This library follows [PSR-1](https://www.php-fig.org/psr/psr-1/) & [PSR-2](https://www.php-fig.org/psr/psr-2/) standards.

Before pushing changes ensure you run the following commands (and they return successfully).

Do not let the code coverage drop.

```bash
vendor/bin/php-cs-fixer fix
vendor/bin/phpunit
vendor/bin/phan
```