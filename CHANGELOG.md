# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## v2.7.0

### Added

- Support Laravel `10.x` and `11.x`

### Changed

- Minimal require PHP version now is `8.1`
- Minimal Laravel version now is `10.0`
- Version of `composer` in docker container updated up to `2.7.4`

## v2.6.0

### Added

- Support Laravel `9.x`

## v2.5.0

### Changed

- Minimal required PHP version now is `7.3`

### Removed

- Dependency `tarampampam/wrappers-php` because this package was deprecated and removed

## v2.4.0

### Added

- Support PHP `8.x`

### Changed

- Composer `2.x` is supported now
- Package `ocramius/package-versions` was replaced with `composer/package-versions-deprecated` version `^1.11`

## v2.3.0

### Changed

- Laravel `8.x` is supported now
- Minimal Laravel version now is `6.0` (Laravel `5.5` LTS got last security update August 30th, 2020)
- Guzzle 7 (`guzzlehttp/guzzle`) is supported
- Dependency `tarampampam/wrappers-php` version `~2.0` is supported

## v2.2.0

### Changed

- Maximal `illuminate/*` packages version now is `7.*`
- CI completely moved from "Travis CI" to "Github Actions" _(travis builds disabled)_
- Minimal required PHP version now is `7.2`

### Added

- PHP 7.4 is supported now

## v2.1.0

### Changed

- Maximal `illuminate/*` packages version now is `6.*`

### Added

- GitHub actions for a tests running

## v2.0.0

### Changed

- Minimal `PHP` version now is `^7.1.3`
- Minimal `Laravel` version now is `5.5.x`
- Maximal `Laravel` version now is `5.8.x`
- Composer scripts
- `QueueInfoInterface` - methods `getName`, `getNodeName`, `getState`, `getVhost` now returns `?string`

## v1.0.1

### Fixed

- `Json::decode` type casting

## v1.0.0

### Changed

- First release

[keepachangelog]:https://keepachangelog.com/en/1.0.0/
[semver]:https://semver.org/spec/v2.0.0.html
