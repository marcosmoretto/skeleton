# DoctrineDataFixture Module for Zend Framework 3

This is fork from [Houndog/DoctrineDataFixtureModule](https://github.com/Hounddog/DoctrineDataFixtureModule). This repository crreated caused by the old repo still using old Doctrine version and old Zend Event Manager, Module Manager and Service Manager (Zend Framework 2).

## Introduction

The DoctrineDataFixtureModule module intends to integrate Doctrine 2 data-fixture with Zend Framework 3 quickly
and easily. The following features are intended to work out of the box:

  - Doctrine ORM support
  - Multiple ORM entity managers
  - Multiple DBAL connections
  - Support reuse existing PDO connections in DBAL


## Installation

Installation of this module uses composer. For composer documentation, please refer to
[getcomposer.org](http://getcomposer.org/).

```sh
$ php composer.phar require aqilix/doctrine-data-fixture-module:0.0.*
```

Then open `config/modules.config.php` and add `DoctrineDataFixtureModule` to your `modules`

#### Registering Fixtures

To register fixtures with Doctrine module add the fixtures in your configuration.

```php
<?php
return [
      'data-fixture' => [
            'fixtures' => __DIR__ . '/../src/ModuleName/Fixture'
      ],
];
```

## Usage

#### Command Line
Access the Doctrine command line as following

##Import
```sh
./vendor/bin/doctrine-module data-fixture:import 
```
