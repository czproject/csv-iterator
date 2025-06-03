<?php

declare(strict_types=1);

use CzProject\CsvIterator\CsvIterator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$iterator = new CsvIterator(__DIR__ . '/csv/basic.csv');

	Assert::same([
		'id' => '1',
		'name' => 'Gandalf The White',
	], $iterator->fetch());

	Assert::same([
		'id' => '2',
		'name' => 'Harry Potter',
	], $iterator->fetch());

	Assert::same([
		'id' => '3',
		'name' => NULL,
	], $iterator->fetch());

	Assert::same([
		'id' => NULL,
		'name' => NULL,
	], $iterator->fetch());

	Assert::null($iterator->fetch()); // EOF
	Assert::null($iterator->fetch()); // closed file
});



test(function () {
	$iterator = new CsvIterator(__DIR__ . '/csv/bom.csv');
	$iterator->setDelimiter(';');

	Assert::same([
		'email' => 'test@example.com',
		'firstName' => 'John',
	], $iterator->fetch());

	Assert::null($iterator->fetch()); // EOF
	Assert::null($iterator->fetch()); // closed file
});
