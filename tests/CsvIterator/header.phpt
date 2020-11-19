<?php

use CzProject\CsvIterator\CsvIterator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$iterator = new CsvIterator(__DIR__ . '/csv/basic.csv');
	$iterator->setHeader(array(
		'id',
		'name',
	));

	Assert::same(array(
		'id' => 'id',
		'name' => 'name',
	), $iterator->fetch());

	Assert::same(array(
		'id' => '1',
		'name' => 'Gandalf The White',
	), $iterator->fetch());

	Assert::same(array(
		'id' => '2',
		'name' => 'Harry Potter',
	), $iterator->fetch());

	Assert::same(array(
		'id' => '3',
		'name' => NULL,
	), $iterator->fetch());

	Assert::same(array(
		'id' => NULL,
		'name' => NULL,
	), $iterator->fetch());

	Assert::null($iterator->fetch()); // EOF
	Assert::null($iterator->fetch()); // closed file
});
