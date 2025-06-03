<?php

declare(strict_types=1);

use CzProject\CsvIterator\CsvIterator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$iterator = new CsvIterator(__DIR__ . '/csv/header.empty-cell-on-end.csv');

	Assert::same([
		'id' => '1',
		'name' => 'Gandalf The White',
	], $iterator->fetch());

	Assert::null($iterator->fetch()); // EOF
	Assert::null($iterator->fetch()); // closed file
});
