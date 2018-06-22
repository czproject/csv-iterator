<?php

use CzProject\CsvIterator\CsvIterator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	Assert::exception(function () {

		$iterator = new CsvIterator(__DIR__ . '/csv/header.empty-cell.csv');
		$iterator->fetch();

	}, 'CzProject\CsvIterator\CsvIteratorException', 'Empty header cell at position 1.');
});


test(function () {
	Assert::exception(function () {

		$iterator = new CsvIterator(__DIR__ . '/csv/header.duplicate.csv');
		$iterator->fetch();

	}, 'CzProject\CsvIterator\CsvIteratorException', 'Duplicate header \'id\'.');
});


test(function () {
	Assert::exception(function () {

		$iterator = new CsvIterator(__DIR__ . '/csv/not-found.csv');
		$iterator->fetch();

	}, 'CzProject\CsvIterator\CsvIteratorException', 'File \'' . __DIR__ . '/csv/not-found.csv' . '\' not found or access denied.');
});
