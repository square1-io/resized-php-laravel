<?php

namespace Square1\Laravel\Resized;

use Square1\Resized\Resized;

class LocalResized extends Resized
{
    private $enabled = true;

    /**
     * Constructor
     *
     * @param string $key
     * @param string $secret
     */
    public function __construct($key, $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }


    /**
     * Determine whether this service is "enabled".
     * If not, no attempt at remote url generation will be made.
     * This prevents applications crashing when .env keys are not set
     * on installation.
     *
     * @param bool $enabled
     */
    public function setEnabled(bool $enabled)
    {
        $this->enabled = $enabled;
    }


    public function isEnabled() : bool
    {
        return $this->enabled;
    }


    /**
    * Process image
    *
    * @param string $url
    * @param int    $width
    * @param int    $height
    * @param string $title
    * @param array  $options
    *
    * @param string
    */
    public function process($url, $width = '', $height = '', $title = '', $options = [])
    {
        if (!$this->isFullUrl($url) || !$this->isEnabled()) {
            return $url;
        }

        return parent::process($url, $width, $height, $title, $options);
    }

    /**
     * Check if the given url is not relative
     *
     * @param string $url
     * @return boolean
     */
    protected function isFullUrl($url) : bool
    {
        $path = parse_url($url);
        return (empty($path['schema']) || empty($path['host']));
    }
}
