<?php

require_once(BASE_PATH.'/vendor/autoload.php');

class SSTexyParser extends TextParser
{
    /** @var Texy */
    protected $texy;

    protected static $ALLOWED_TAGS_CONFIG = array(
            'all' => Texy::ALL,
            'none' => Texy::NONE
        );

    public function __construct($content = "")
    {
        Object::__construct(); //FIXME: This should be done by TextParser
        parent::__construct($content);

        $this->texy = new SS_Texy();
        $this->texy->setOutputMode(Texy::HTML5);

        $this->setAllowedTags($this->config()->get('allowed_tags'));

        $this->texy->headingModule->top = $this->config()->get('top_heading');
    }

    public function setAllowedTags($tags)
    {
        if (array_key_exists($tags, self::$ALLOWED_TAGS_CONFIG)) {
            $this->texy->allowedTags = self::$ALLOWED_TAGS_CONFIG[$tags];
        } else {
            $this->texy->allowedTags = $tags;
        }
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
        foreach ($args as $what) {
            $this->texy->allowed[$what] = false;
        }
    }

    public function enable()
    {
        $args = func_get_args();
        foreach ($args as $what) {
            $this->texy->allowed[$what] = true;
        }
    }
}
