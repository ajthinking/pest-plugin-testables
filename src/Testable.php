<?php

namespace Ajthinking\PestPluginTestables;

use PHPUnit\Framework\Assert;

class Testable
{
	public mixed $target;
	public array $operationsStack = [];

	public function __construct(mixed $target)
	{
		$this->target = $target;
	}

	public function __get(string $name): self
	{
		array_push($this->operationsStack, $name);

		return $this;
	}

	public function __call(string $name, $args): self
	{
		if(str_starts_with($name, 'assert')) {
			return $this->makeAssertion($name, $args);
		}

		array_push($this->operationsStack, [$name, ...$args]);

		return $this;
	}

	protected function makeAssertion($name, $args): self
	{
		Assert::$name(...[
			...$args,
			$this->evaluateStack()
		]);
		
		return $this->continue();
	}

	protected function evaluateStack()
	{
		for($i = 0; $i < sizeof($this->operationsStack)-1; $i++) {
			$this->target = $this->evaluateOperation(
				$this->operationsStack[$i]
			);
		}

		return empty($this->operationsStack)
			? $this->target
			: $this->evaluateOperation(end($this->operationsStack));
	}

	protected function evaluateOperation(string | array $operation): mixed
	{
		
		if(is_string($operation)) {
			return $this->target->{$operation};
		}

		if(is_array($operation)) {
			$method = $operation[0];
			$args = array_slice($operation, 1);
			return $this->target->$method(...$args);
		}

		return $this->target;		
	}

	protected function getActual(): mixed
	{
		if(is_string($this->operation)) {
			return $this->target->{$this->operation};
		}

		if(is_array($this->operation)) {
			$method = $this->operation[0];
			$args = array_slice($this->operation, 1);
			return $this->target->$method($args);
		}

		return $this->target;
	}

	protected function continue(): self
	{
		$this->operationsStack = [];

		return $this;
	}
}