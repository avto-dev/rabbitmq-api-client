# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog][keepachangelog] and this project adheres to [Semantic Versioning][semver].

## Unreleased

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
