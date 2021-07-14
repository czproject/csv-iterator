<?php

use CzProject\CsvIterator\CsvIterator;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';


test(function () {
	$iterator = new CsvIterator(__DIR__ . '/csv/encoding.windows-1250.csv');
	$iterator->setEncoding(CsvIterator::ENCODING_WINDOWS_1250);

	Assert::same([
		'id' => '1',
		'name' => 'Příliš žluťoučký kůň úpěl ďábelské ódy.',
	], $iterator->fetch());
});
