<?php

declare(strict_types=1);

use CzProject\CsvIterator\CsvIterator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	Assert::exception(function () {

		$iterator = new CsvIterator(__DIR__ . '/csv/header.empty-cell.csv');
		$iterator->fetch();

	}, CzProject\CsvIterator\ParseException::class, 'Empty header cell at position 1.');
});


test(function () {
	Assert::exception(function () {

		$iterator = new CsvIterator(__DIR__ . '/csv/header.duplicate.csv');
		$iterator->fetch();

	}, CzProject\CsvIterator\ParseException::class, 'Duplicate header \'id\'.');
});


test(function () {
	Assert::exception(function () {

		$iterator = new CsvIterator(__DIR__ . '/csv/not-found.csv');
		$iterator->fetch();

	}, CzProject\CsvIterator\IOException::class, 'File \'' . __DIR__ . '/csv/not-found.csv' . '\' not found or access denied.');
});


test(function () {
	$iterator = new CsvIterator(__DIR__ . '/csv/basic.csv');
	$iterator->fetch();

	Assert::exception(function () use ($iterator) {
		$iterator->setDelimiter(';');

	}, CzProject\CsvIterator\InvalidStateException::class, 'Delimiter can be changed before reading started only.');

	Assert::exception(function () use ($iterator) {
		$iterator->setEnclosure('"');

	}, CzProject\CsvIterator\InvalidStateException::class, 'Enclosure can be changed before reading started only.');

	Assert::exception(function () use ($iterator) {
		$iterator->setEscape('\\');

	}, CzProject\CsvIterator\InvalidStateException::class, 'Escape char can be changed before reading started only.');

	Assert::exception(function () use ($iterator) {
		$iterator->setEncoding('UTF-8');

	}, CzProject\CsvIterator\InvalidStateException::class, 'Encoding can be changed before reading started only.');
});
