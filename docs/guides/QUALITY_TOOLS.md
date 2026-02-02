# Quality Assurance Tools

This project implements several tools to maintain high code quality and consistency:

## PHPStan

Static analysis tool for finding bugs and inconsistencies.

### Usage:
```bash
./vendor/bin/phpstan analyze --level=5
```

## PHP CodeSniffer

Ensures code follows PSR-12 standards and project-specific coding standards.

### Usage:
```bash
# Check for coding standard violations
./vendor/bin/phpcs --standard=PSR12 --extensions=php --report=summary .

# Automatically fix coding standard violations
./vendor/bin/phpcbf --standard=PSR12 --extensions=php .
```

## PHP-CS-Fixer

Automatically fixes coding standards issues.

### Usage:
```bash
# Dry run to see what needs to be fixed
./vendor/bin/php-cs-fixer fix --dry-run --diff

# Apply fixes
./vendor/bin/php-cs-fixer fix
```

## Configuration Files

- `phpstan.neon.dist` - PHPStan configuration
- `phpcs.xml` - PHP CodeSniffer rules
- `.php_cs.dist` - PHP-CS-Fixer rules
- `.github/workflows/ci.yml` - CI workflow configuration

## CI/CD Integration

The quality tools run automatically on each commit through the GitHub Actions workflow defined in `.github/workflows/ci.yml`.