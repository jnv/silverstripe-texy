<?php

require_once(BASE_PATH.'/vendor/autoload.php');

class SS_Texy extends Texy
{

	protected $cache;
	public static $cacheOpts = array(
	);

	function __construct()
	{
		parent::__construct();

		$this->cache = SS_Cache::factory('SS_Texy', 'Output', self::$cacheOpts);
		$this->addHandler('script', array($this, 'scriptHandler'));
	}

	function process($text, $singleLine = FALSE)
	{
		$key = md5($text);
		if (!($html = $this->cache->load($key)))
		{
			$html = parent::process($text, $singleLine);
			$this->cache->save($html);
		}

		return $html;
	}

	function processTypo($text)
	{
		$key = md5($text);
		if (!($html = $this->cache->load($key)))
		{
			$html = parent::processTypo($text);
			$this->cache->save($html);
		}

		return $html;
	}

	function scriptHandler($invocation, $cmd, $args, $raw)
	{
		switch ($cmd)
		{
			case 'headingTop':
				$level = intval($args[0]);
				if (($level >= 1) && ($level <= 6))
					$invocation->getTexy()->headingModule->top = $level;
				return '';

			default: // neumime zpracovat, zavolame dalsi handler v rade
				return $invocation->proceed();
		}
	}

}
