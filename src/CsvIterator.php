<?php

	namespace CzProject\CsvIterator;


	class CsvIterator
	{
		/** @var string */
		private $file;

		/** @var resource|NULL */
		private $pointer = NULL;

		/** @var array|NULL */
		private $header = NULL;

		/** @var string */
		private $delimiter = ',';

		/** @var string */
		private $enclosure = '"';

		/** @var string */
		private $escape = '\\';

		/** @var bool */
		private $eof = FALSE;

		/** @var bool */
		private $inProgress = FALSE;


		public function __construct($file)
		{
			$this->file = $file;
		}


		/**
		 * @param  string
		 * @return self
		 */
		public function setDelimiter($delimiter)
		{
			if ($this->inProgress) {
				throw new InvalidStateException("Delimiter can be changed before reading started only.");
			}

			$this->delimiter = $delimiter;
			return $this;
		}


		/**
		 * @param  string
		 * @return self
		 */
		public function setEnclosure($enclosure)
		{
			if ($this->inProgress) {
				throw new InvalidStateException("Enclosure can be changed before reading started only.");
			}

			$this->enclosure = $enclosure;
			return $this;
		}


		/**
		 * @param  string
		 * @return self
		 */
		public function setEscape($escape)
		{
			if ($this->inProgress) {
				throw new InvalidStateException("Escape char can be changed before reading started only.");
			}

			$this->escape = $escape;
			return $this;
		}


		/**
		 * @return array|NULL
		 * @throws CsvIteratorException
		 */
		public function fetch()
		{
			if ($this->eof) {
				return NULL;
			}

			$this->open();
			$data = NULL;

			do {
				$data = fgetcsv($this->pointer, 0, $this->delimiter, $this->enclosure, $this->escape);

				if ($data === FALSE || $data === NULL) {
					$this->eof = TRUE;
					fclose($this->pointer);
					return NULL;
				}

				if (is_array($data) && count($data) === 1 && $data[0] === NULL) { // empty line
					$data = NULL;
				}

			} while (!is_array($data) || empty($data));

			if ($this->header === NULL) { // parse header
				$this->header = array();

				foreach ($data as $i => $value) {
					$value = $this->normalizeValue($value);

					if ($value === '') {
						throw new ParseException('Empty header cell at position ' . $i . '.');
					}

					if (isset($this->header[$value])) {
						throw new ParseException('Duplicate header \'' . $value . '\'.');
					}

					$this->header[$value] = $i;
				}

				return $this->fetch(); // fetch next row

			}

			// parse data
			$row = array();

			foreach ($this->header as $column => $i) {
				$value = NULL;

				if (isset($data[$i])) {
					$value = $this->normalizeValue($data[$i]);
				}

				$row[$column] = $value !== '' ? $value : NULL;
			}

			return $row;
		}


		private function open()
		{
			if ($this->pointer === NULL) {
				$f = @fopen($this->file, 'r'); // @ intentionally

				if ($f === FALSE) {
					throw new IOException('File \'' . $this->file . '\' not found or access denied.');
				}

				$this->pointer = $f;
				$this->inProgress = TRUE;
			}
		}


		private function normalizeValue($value)
		{
			if ($this->escape !== '' && $this->enclosure !== '') { // fgetcsv() dosn't return unescaped strings, see http://php.net/manual/en/function.fgetcsv.php#119896
				$escapeChar = $this->escape . $this->enclosure;
				$value = strtr($value, array(
					$escapeChar => $this->enclosure,
				));
			}
			return trim($value);
		}
	}
