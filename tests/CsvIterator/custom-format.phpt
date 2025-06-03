<?php

declare(strict_types=1);

use CzProject\CsvIterator\CsvIterator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$iterator = new CsvIterator(__DIR__ . '/csv/custom-format.csv');
	$iterator->setDelimiter(';');
	$iterator->setEnclosure('\'');
	$iterator->setEscape('\\');

	Assert::same([
		'id' => '1',
		'name' => 'Potter \' Harry',
	], $iterator->fetch());

});
