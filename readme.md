
# CsvIterator

Simple reading of CSV files.

[![Build Status](https://travis-ci.org/czproject/csv-iterator.svg?branch=master)](https://travis-ci.org/czproject/csv-iterator)

<a href="https://www.patreon.com/bePatron?u=9680759"><img src="https://c5.patreon.com/external/logo/become_a_patron_button.png" alt="Become a Patron!" height="35"></a>
<a href="https://www.paypal.me/janpecha/1eur"><img src="https://buymecoffee.intm.org/img/button-paypal-white.png" alt="Buy me a coffee" height="35"></a>


## Installation

[Download a latest package](https://github.com/czproject/csv-iterator/releases) or use [Composer](http://getcomposer.org/):

```
composer require czproject/csv-iterator
```

CsvIterator requires PHP 5.4.0 or later.


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
