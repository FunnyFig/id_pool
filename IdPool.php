<?php

namespace FunnyFig;

class IdPool {
	protected $min;
	protected $max;
	protected $cur;
	protected $alloc = [];
	protected $free = [];

	function __construct(int $max=PHP_INT_MAX, int $min=0)
	{
		assert($max>$in, "max($max) should be greater than min($min)");
		$this->max = $max;
		$this->min = $this->cur = $min;
	}

	function new_id()
	{
		if ($this->free) {
			// inefficient?
			$rv = array_shift($this->free);
			$this->alloc[$rv] = true;
			return $rv;
		}

		while (!empty($this->alloc[$this->cur])) {
			if ($this->cur == $this->max)
				$this->cur = $this->min;
			else
				++$this->cur;
		}

		$this->alloc[$this->cur] = true;
		return $this->cur;
	}

	function free_id($id)
	{
		if (!isset($this->alloc[$id]))
			return;
		unset($this->alloc[$id]);
		$this->free[] = $id;
	}
}

