<?php

namespace Square1\Laravel\Resized;

use Square1\Resized\Resized;

class LocalResized extends Resized
{
    private bool $enabled = true;

    /**
     * Bypasses parent validation so LocalResized can be instantiated
     * without credentials when the package is not yet configured.
     */
    public function __construct(string $key, string $secret)
    {
        $this->key = $key;
        $this->secret = $secret;
    }

    /**
     * When disabled, all process() calls return the original URL unchanged.
     * Used to prevent crashes when env credentials are not set.
     */
    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function process(string $url, string $width = '', string $height = '', string $title = '', array $options = []): string
    {
        if (! $this->isFullUrl($url) || ! $this->isEnabled()) {
            return $url;
        }

        return parent::process($url, $width, $height, $title, $options);
    }

    protected function isFullUrl(string $url): bool
    {
        $path = parse_url($url);

        return ! empty($path['scheme']) && ! empty($path['host']);
    }
}
