# chubbyphp-validation

[![Build Status](https://api.travis-ci.org/chubbyphp/chubbyphp-validation.png?branch=master)](https://travis-ci.org/chubbyphp/chubbyphp-validation)
[![Total Downloads](https://poser.pugx.org/chubbyphp/chubbyphp-validation/downloads.png)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Latest Stable Version](https://poser.pugx.org/chubbyphp/chubbyphp-validation/v/stable.png)](https://packagist.org/packages/chubbyphp/chubbyphp-validation)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/chubbyphp/chubbyphp-validation/?branch=master)

## Description

A simple validation.

## Requirements

 * php: ~7.0

## Suggest

 * pimple/pimple: ~3.0

## Installation

Through [Composer](http://getcomposer.org) as [chubbyphp/chubbyphp-validation][1].

```sh
composer require chubbyphp/chubbyphp-validation "~2.0@alpha"
```

## Usage

### Validator

```php
<?php

use Chubbyphp\Validation\Error\NestedErrorMessages;
use Chubbyphp\Validation\Registry\ObjectMappingRegistry;
use Chubbyphp\Validation\Validator;
use MyProject\Model\Model;
use MyProject\Validation\ModelMapping;

$validator = new Validator(ObjectMappingRegistry([new ModelMapping]));

$model = new Model();

$errors = $validator->validateObject($model);

$errorMessages = new NestedErrorMessages($errors);
$errorMessages->getMessages(); // ['propertyName' => ['constraint.notnull.null']]

$model->setPropertyName('');

$errors = $validator->validateObject($model);
// [];
```

### Mapping

#### [PropertyMapping][2]

#### ObjectMapping (ObjectMappingInterface)

```php
<?php

namespace MyProject\Validation;

use Chubbyphp\Validation\Constraint\NotNullConstraint;
use Chubbyphp\Validation\Mapping\ObjectMappingInterface;
use Chubbyphp\Validation\Mapping\PropertyMapping;
use Chubbyphp\Validation\Mapping\PropertyMappingInterface;
use MyProject\Model\Model;

class ModelMapping implements ObjectMappingInterface
{
    /**
     * @return string
     */
    public function getClass(): string
    {
        return Model::class;
    }

    /**
     * @return ConstraintInterface[]
     */
    public function getConstraints(): array
    {
        return [];
    }

    /**
     * @return PropertyMappingInterface[]
     */
    public function getPropertyMappings(): array
    {
        return [
            new PropertyMapping('propertyName', [new NotNullConstraint()]),
        ];
    }
}
```

### Constraint

* [CountConstraint][20]
* [DateConstraint][21]
* [EmailConstraint][22]
* [NotBlankConstraint][23]
* [NotNullConstraint][24]
* [NumericConstraint][25]
* [NumericRangeConstraint][26]

### Error

* [Error][3]
* [ErrorMessages][4]
* [NestedErrorMessages][5]

### Registry

* [ObjectMappingRegistry][6]

## Copyright

Dominik Zogg 2017


[1]: https://packagist.org/packages/chubbyphp/chubbyphp-validation

[2]: doc/Mapping/PropertyMapping.md

[3]: doc/Error/Error.md
[4]: doc/Error/ErrorMessages.md
[5]: doc/Error/NestedErrorMessages.md
[6]: doc/Registry/ObjectMappingRegistry.md

[20]: doc/Constraint/CountConstraint.md
[21]: doc/Constraint/DateConstraint.md
[22]: doc/Constraint/EmailConstraint.md
[23]: doc/Constraint/NotBlankConstraint.md
[24]: doc/Constraint/NotNullConstraint.md
[25]: doc/Constraint/NumericConstraint.md
[26]: doc/Constraint/NumericRangeConstraint.md
