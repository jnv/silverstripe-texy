<?php

class SSTexyParser extends TextParser
{
	/** @var Texy */
	protected $texy;

	protected $cache;

	protected $cacheOpts = array();

	public function __construct($content = "")
	{
		parent::__construct($content);

		$this->texy = new SS_Texy();
		$this->texy->setOutputMode(Texy::HTML5);
		$this->texy->allowedTags = Texy::ALL;
		$this->texy->headingModule->top = 2;

	}

	public function getTexy()
	{
		return $this->texy;
	}

	public function parse()
	{
		return $this->texy->process($this->content);
	}

	public function typo()
	{
		return $this->texy->processTypo($this->content);
	}

	public function disable()
	{
		$args = func_get_args();
		foreach($args as $what)
		{
			$this->texy->allowed[$what] = false;
		}
	}

	public function enable()
	{
		$args = func_get_args();
		foreach($args as $what)
		{
			$this->texy->allowed[$what] = true;
		}
	}
}
