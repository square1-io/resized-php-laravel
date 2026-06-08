<?php

namespace Square1\Laravel\Resized\Test;

use PHPUnit\Framework\TestCase;
use Square1\Laravel\Resized\LocalResized;

class LocalResizedTest extends TestCase
{
    private LocalResized $resized;

    protected function setUp(): void
    {
        $this->resized = new LocalResized('key', 'secret-d0be2dc421be4fcd0172e5afceea3970e2f3d940');
    }

    public function testIsEnabledByDefault(): void
    {
        $this->assertTrue($this->resized->isEnabled());
    }

    public function testCanBeDisabled(): void
    {
        $this->resized->setEnabled(false);

        $this->assertFalse($this->resized->isEnabled());
    }

    public function testRelativeUrlPassesThrough(): void
    {
        $this->assertEquals('/relative/path.jpg', $this->resized->process('/relative/path.jpg', '100', '100'));
    }

    public function testDisabledPassesFullUrlThrough(): void
    {
        $this->resized->setEnabled(false);

        $url = 'https://example.com/image.jpg';

        $this->assertEquals($url, $this->resized->process($url, '100', '100'));
    }

    public function testEnabledProcessesFullUrl(): void
    {
        $this->assertEquals(
            'https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwczpcXFwvXFxcL2V4YW1wbGUuY29tXFxcL2ltYWdlLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHBzOlxcXC9cXFwvaW1nLnJlc2l6ZWQuY29cXFwvbm8taW1hZ2UucG5nXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjZiNzIxZjk0MTM1MDlhMTRmYmY5M2ExNTljYmYzNTU0NTYyNTcyNzUifQ==/image.jpg',
            $this->resized->process('https://example.com/image.jpg', '100', '100')
        );
    }

    public function testIntegerDimensionsAreAccepted(): void
    {
        $this->assertEquals(
            'https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwczpcXFwvXFxcL2V4YW1wbGUuY29tXFxcL2ltYWdlLmpwZ1wiLFwid2lkdGhcIjoxMDAsXCJoZWlnaHRcIjoyMDAsXCJkZWZhdWx0XCI6XCJodHRwczpcXFwvXFxcL2ltZy5yZXNpemVkLmNvXFxcL25vLWltYWdlLnBuZ1wiLFwib3B0aW9uc1wiOltdfSIsImhhc2giOiJlYTNkNDhlMzQ5ZGEyZTBlYjg3NDAzMjZlZDk5MjdjM2I0Yjc1YjRmIn0=/image.jpg',
            $this->resized->process('https://example.com/image.jpg', 100, 200)
        );
    }
}
