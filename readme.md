# CsvIterator

[![Build Status](https://github.com/czproject/csv-iterator/workflows/Build/badge.svg)](https://github.com/czproject/csv-iterator/actions)
[![Downloads this Month](https://img.shields.io/packagist/dm/czproject/csv-iterator.svg)](https://packagist.org/packages/czproject/csv-iterator)
[![Latest Stable Version](https://poser.pugx.org/czproject/csv-iterator/v/stable)](https://github.com/czproject/csv-iterator/releases)
[![License](https://img.shields.io/badge/license-New%20BSD-blue.svg)](https://github.com/czproject/csv-iterator/blob/master/license.md)

Simple reading of CSV files.

<a href="https://www.janpecha.cz/donate/"><img src="https://buymecoffee.intm.org/img/donate-banner.v1.svg" alt="Donate" height="100"></a>


## Installation

[Download a latest package](https://github.com/czproject/csv-iterator/releases) or use [Composer](http://getcomposer.org/):

```
composer require czproject/csv-iterator
```

CsvIterator requires PHP 5.6.0 or later.


## Usage

```csv
id,name
1,Gandalf The White
```

```php
$iterator = new CzProject\CsvIterator\CsvIterator('/path/to/file.csv');

// optional:
$iterator->setDelimiter(',');
$iterator->setEnclosure('"');
$iterator->setEscape('\\');
$iterator->setEncoding('UTF-8');

while (($row = $iterator->fetch()) !== NULL) {
	echo $row['id']; // prints '1'
	echo $row['name']; // prints 'Gandalf The White'
}
```

------------------------------

License: [New BSD License](license.md)
<br>Author: Jan Pecha, https://www.janpecha.cz/
