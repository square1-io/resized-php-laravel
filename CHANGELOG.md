# Changelog

All notable changes to this project will be documented in this file.

## [3.0.0] - 2026-06-04

### Changed

- Added support for Laravel 10, 11, 12, and 13.
- Dropped support for Laravel 8 and 9.
- Dropped support for PHP 7.x (EOL).
- Set minimum PHP requirement to 8.2.
- Updated `square1/resized` requirement to `^1.3`.
- Updated Orchestra Testbench dev dependency to support versions 8 through 11.
- Updated phpunit.xml.dist to the PHPUnit 13.1 schema.
- Fixed `use Log` global import in `ResizedServiceProvider` to use the fully qualified `Illuminate\Support\Facades\Log`.

## [2.2.0]

- Maintenance release.

## [2.1.0]

- Maintenance release.

## [2.0]

- Added Laravel 7 support.
- Removed support for PHP 7.1.

## [1.1]

- Added support for relative URL pass-through (LocalResized).
- Added support for image processing options.

## [1.0.3]

- Bug fixes.

## [1.0.2]

- Bug fixes.

## [1.0.1]

- Bug fixes.

## [1.0]

- Initial release.
