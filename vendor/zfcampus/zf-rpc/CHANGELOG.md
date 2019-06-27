# Changelog

All notable changes to this project will be documented in this file, in reverse chronological order by release.

## 1.4.2 - 2019-01-22

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#20](https://github.com/zfcampus/zf-rpc/pull/20) fixes a circular dependency lookup issue that would result in nested
  wrapping of a callable for use as an RPC controller.

## 1.4.1 - 2019-01-07

### Added

- Nothing.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#19](https://github.com/zfcampus/zf-rpc/pull/19) fixes a circular dependency issue in the `RpcControllerFactory`.

## 1.4.0 - 2018-05-03

### Added

- [#17](https://github.com/zfcampus/zf-rpc/pull/17) adds support for PHP 7.1 and 7.2.

### Changed

- Nothing.

### Deprecated

- Nothing.

### Removed

- [#17](https://github.com/zfcampus/zf-rpc/pull/17) removes support for HHVM.

### Fixed

- Nothing.

## 1.3.1 - 2016-10-11

### Added

- Nothing.

### Deprecated

- Nothing.

### Removed

- Nothing.

### Fixed

- [#11](https://github.com/zfcampus/zf-rpc/pull/11) fixes a typo in an import
  statement.
- [#12](https://github.com/zfcampus/zf-rpc/pull/12) fixes registration of the
  `ViewJsonFactory`, ensuring it registers at its original priority of 100.

## 1.3.0 - 2016-07-12

### Added

- [#10](https://github.com/zfcampus/zf-rpc/pull/10) adds support for v3 releases
  of Zend Framework components, while retaining compatibility with v2 releases.
- [#10](https://github.com/zfcampus/zf-rpc/pull/10) adds
  `ZF\Rpc\Factory\OptionsListenerFactory`, which was extracted from the `Module`
  class.
- [#10](https://github.com/zfcampus/zf-rpc/pull/10) exposes the module to
  zend-component-installer.

### Deprecated

- Nothing.

### Removed

- [#10](https://github.com/zfcampus/zf-rpc/pull/10) removes support for PHP 5.5.

### Fixed

- Nothing.
