<?php

declare(strict_types=1);

use CzProject\CsvIterator\CsvIterator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$iterator = new CsvIterator(__DIR__ . '/csv/header-block.csv');

	while ($iterator->consumeLine() !== NULL) {
		// skip line
	}

	Assert::same([
		'id' => '1',
		'name' => 'Gandalf The White',
		'status' => '1',
	], $iterator->fetch());

	Assert::same([
		'id' => '2',
		'name' => 'Harry Potter',
		'status' => '0',
	], $iterator->fetch());

	Assert::null($iterator->fetch()); // EOF
	Assert::null($iterator->fetch()); // closed file
});
