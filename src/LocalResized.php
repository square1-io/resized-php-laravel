<?php

namespace Square1\Laravel\Resized;

use Square1\Resized\Resized;

class LocalResized extends Resized
{
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
        if (! $this->isFullUlr($url)) {
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
    protected function isFullUlr($url)
    {
        $path = parse_url($url);
        return (empty($path['schema']) || empty($path['host']));
    }
}
