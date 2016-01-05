<?php

require_once(BASE_PATH.'/vendor/autoload.php');

class SS_Texy extends Texy
{
    /**
     * @var SS_Cache
     */
    protected $cache = null;

    public function __construct()
    {
        parent::__construct();

        $this->cache = SS_Cache::factory('SS_Texy');
        $this->addHandler('script', array($this, 'scriptHandler'));
    }

    public function config()
    {
        if (!$this->_config_forclass) {
            $this->_config_forclass = Config::inst()->forClass('SS_Texy');
        }

        return $this->_config_forclass;
    }

    public function cacheKey($text)
    {
        return md5($text);
    }

    public function process($text, $singleLine = false)
    {
        $key = $this->cacheKey($text);
        if (!($html = $this->cache->load($key))) {
            $html = parent::process($text, $singleLine);
            $this->cache->save($html);
        }

        return $html;
    }

    public function processTypo($text)
    {
        $key = $this->cacheKey($text);
        if (!($html = $this->cache->load($key))) {
            $html = parent::processTypo($text);
            $this->cache->save($html);
        }

        return $html;
    }

    public function scriptHandler($invocation, $cmd, $args, $raw)
    {
        switch ($cmd) {
            case 'headingTop':
                $level = intval($args[0]);
                if (($level >= 1) && ($level <= 6)) {
                    $invocation->getTexy()->headingModule->top = $level;
                }
                return '';

            default:
                return $invocation->proceed();
        }
    }
}
