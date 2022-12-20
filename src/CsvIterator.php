<?php

	namespace CzProject\CsvIterator;


	class CsvIterator
	{
		const ENCODING_UTF_8 = 'UTF-8';
		const ENCODING_WINDOWS_1250 = 'WINDOWS-1250';

		/** @var string */
		private $file;

		/** @var resource|NULL */
		private $pointer = NULL;

		/** @var array<string, int>|NULL */
		private $header = NULL;

		/** @var string */
		private $delimiter = ',';

		/** @var string */
		private $enclosure = '"';

		/** @var string */
		private $escape = '\\';

		/** @var string */
		private $encoding = self::ENCODING_UTF_8;

		/** @var bool */
		private $eof = FALSE;

		/** @var bool */
		private $inProgress = FALSE;


		/**
		 * @param string $file
		 */
		public function __construct($file)
		{
			$this->file = $file;
		}


		/**
		 * @param  string $delimiter
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
		 * @param  string $enclosure
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
		 * @param  string $escape
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
		 * @param  string $encoding
		 * @return self
		 */
		public function setEncoding($encoding)
		{
			if ($this->inProgress) {
				throw new InvalidStateException("Encoding can be changed before reading started only.");
			}

			$this->encoding = $encoding;
			return $this;
		}


		/**
		 * @param  string[] $header
		 * @return self
		 */
		public function setHeader(array $header)
		{
			if ($this->inProgress) {
				throw new InvalidStateException("Header can be changed before reading started only.");
			}

			if (empty($header)) {
				throw new InvalidArgumentException('Header cannot be empty.');
			}

			$this->header = [];
			$i = 0;

			foreach ($header as $value) {
				if (!is_string($value)) {
					throw new InvalidArgumentException('Header name must be string.');
				}

				$this->header[$value] = $i;
				$i++;
			}

			return $this;
		}


		/**
		 * @return string|NULL
		 * @throws Exception
		 */
		public function consumeLine()
		{
			if ($this->eof) {
				return NULL;
			}

			$this->open();

			if ($this->pointer === NULL) {
				throw new IOException('Missing file pointer.');
			}

			$s = fgets($this->pointer);

			if ($s === FALSE) {
				$this->eof = TRUE;
				fclose($this->pointer);
				return NULL;
			}

			$s = trim($s);
			return $s !== '' ? $s : NULL;
		}


		/**
		 * @return array<string, string|NULL>|NULL
		 * @throws Exception
		 */
		public function fetch()
		{
			if ($this->eof) {
				return NULL;
			}

			$this->open();
			$data = NULL;

			do {
				assert($this->pointer !== NULL);
				$data = fgetcsv($this->pointer, 0, $this->delimiter, $this->enclosure, $this->escape);

				if ($data === FALSE || !is_array($data)) {
					$this->eof = TRUE;
					fclose($this->pointer);
					return NULL;
				}

				if (is_array($data) && count($data) === 1 && $data[0] === NULL) { // empty line
					$data = NULL;
				}

			} while (!is_array($data) || empty($data));

			if ($this->header === NULL) { // parse header
				$this->header = [];
				$wasLastEmpty = FALSE;

				foreach ($data as $i => $value) {
					if ($wasLastEmpty) {
						throw new ParseException('Empty header cell at position ' . ($i - 1) . '.');
					}

					$value = $this->normalizeValue((string) $value);
					$wasLastEmpty = FALSE;

					if ($value === '') {
						$wasLastEmpty = TRUE;
						continue;
					}

					if (isset($this->header[$value])) {
						throw new ParseException('Duplicate header \'' . $value . '\'.');
					}

					$this->header[$value] = $i;
				}

				return $this->fetch(); // fetch next row

			}

			// parse data
			$row = [];

			foreach ($this->header as $column => $i) {
				$value = NULL;

				if (isset($data[$i])) {
					$value = $this->normalizeValue($data[$i]);
				}

				$row[$column] = $value !== '' ? $value : NULL;
			}

			return $row;
		}


		/**
		 * @return void
		 */
		private function open()
		{
			if ($this->pointer === NULL) {
				$f = @fopen($this->file, 'r'); // @ intentionally

				if ($f === FALSE) {
					throw new IOException('File \'' . $this->file . '\' not found or access denied.');
				}

				if ($this->encoding === self::ENCODING_UTF_8 && fgets($f, 4) !== "\xEF\xBB\xBF") {
					rewind($f);
				}

				$this->pointer = $f;
				$this->inProgress = TRUE;
			}
		}


		/**
		 * @param  string $value
		 * @return string
		 */
		private function normalizeValue($value)
		{
			if ($this->encoding !== self::ENCODING_UTF_8) {
				$value = iconv($this->encoding, 'UTF-8//TRANSLIT', $value);

				if (!is_string($value)) {
					throw new InvalidStateException('Iconv failed.');
				}
			}

			if ($this->escape !== '' && $this->enclosure !== '') { // fgetcsv() dosn't return unescaped strings, see http://php.net/manual/en/function.fgetcsv.php#119896
				$escapeChar = $this->escape . $this->enclosure;
				$value = strtr($value, [
					$escapeChar => $this->enclosure,
				]);
			}

			return trim($value);
		}
	}
