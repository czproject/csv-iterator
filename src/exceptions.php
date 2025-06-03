<?php

	declare(strict_types=1);

	namespace CzProject\CsvIterator;


	class Exception extends \RuntimeException
	{
	}


	class InvalidArgumentException extends Exception
	{
	}


	class InvalidStateException extends Exception
	{
	}


	class IOException extends Exception
	{
	}


	class ParseException extends Exception
	{
	}
