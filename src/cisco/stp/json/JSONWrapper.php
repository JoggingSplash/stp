<?php

namespace cisco\stp\json;

final class JSONWrapper extends \JsonException {

	/**
	 * @param array $json
	 */
    private function __construct(private array $json){
        parent::__construct();
    }

	/**
	 * @param array $json
	 * @return self
	 */
	static public function wrap(array $json): self {
		return new self($json);
	}

	/**
	 * @param mixed $key
	 * @return mixed
	 * @throws JSONWrapper
	 */
	public function mustHave(mixed $key): mixed {
		if (!array_key_exists($key, $this->json)) {
			$this->message = "Trying to get offset of $key at " . self::class . " json=" . implode(", ", $this->json);
			throw $this;
		}

		return $this->json[$key]; // puede ser null y está bien
	}

}