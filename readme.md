
# CsvIterator

[![Tests Status](https://github.com/czproject/csv-iterator/workflows/Tests/badge.svg)](https://github.com/czproject/csv-iterator/actions)

Simple reading of CSV files.


## Support Me

Do you like CsvIterator? Are you looking forward to the **new features**?

<a href="https://www.paypal.com/donate?hosted_button_id=BWR5RJCDLY7SG"><img src="https://buymecoffee.intm.org/img/janpecha-paypal-donate@2x.png" alt="PayPal or credit/debit card" width="254" height="248"></a>

<img src="https://buymecoffee.intm.org/img/bitcoin@2x.png" alt="Bitcoin" height="32"> `bc1qrq9egf99a6z3576twggrp6uv5td5r3pq0j4awe`

Thank you!


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
