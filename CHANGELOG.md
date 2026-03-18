# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/), and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## Unreleased

### Changed
- Upgrade minimum PHP version to ^8.5
- Modernise codebase with Rector (strict types, type declarations, early returns)
- Update PHPUnit to ^13.0
- Update Symfony packages to ^7.2|^8.0
- Replace PHP_CodeSniffer with Laravel Pint
- Add PHPStan static analysis at level 8
- Update phpunit.xml schema to PHPUnit 13.0
- Standardise CI workflows (tests, codecov, security audit, dependabot)
- Update license year to 2026

### Added
- PHP version badge in README
- Requirements section in README
- Security audit workflow
- Dependabot configuration

### Removed
- squizlabs/php_codesniffer dependency

## [2.1.0](https://github.com/myerscode/utilities-files/releases/tag/2.1.0) - 2023-10-10

- [`f8ac495`](https://github.com/myerscode/utilities-files/commit/f8ac495b410e40b62bcceb3f95bd0583e94c7f5f) chore: updated test scripts to enable/disable coverage
- [`91ace25`](https://github.com/myerscode/utilities-files/commit/91ace255f34f434ce3dbb664b036b61bbf25d890) feature: added method to check if the path is absolute
- [`f88b175`](https://github.com/myerscode/utilities-files/commit/f88b175753de154b3aba60569a1ea2327e17f8a0) wip method for checking if a path is absolute
- [`924bb34`](https://github.com/myerscode/utilities-files/commit/924bb3478757d03eb7b493ebf8efa923070c52ce) feat(touch) added ability to touch a file or directory independently
- [`0288f22`](https://github.com/myerscode/utilities-files/commit/0288f22a013c22dbe60a791faeb1244b57409939) Merge tag '2.0.2' into develop
