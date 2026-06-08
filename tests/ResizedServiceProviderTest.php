<?php

namespace Square1\Laravel\Resized\Test;

use Illuminate\Foundation\Application;
use InvalidArgumentException;
use JsonException;
use Orchestra\Testbench\TestCase;
use Square1\Laravel\Resized\ResizedFacade as Resized;
use Square1\Laravel\Resized\ResizedServiceProvider;

abstract class ResizedServiceProviderTest extends TestCase
{
    public function testRegisterResizedServiceProvider(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);

        $resized = $app['resized'];
        $this->assertInstanceOf('Square1\Resized\Resized', $resized);
    }

    public function testConfigData(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        $config = $app['config']->get('resized');

        $this->assertArrayHasKey('host', $config);
        $this->assertArrayHasKey('key', $config);
        $this->assertArrayHasKey('secret', $config);
        $this->assertArrayHasKey('default', $config);
    }

    public function testInvalidDefaultURL(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        $this->expectException(InvalidArgumentException::class, 'Invalid Default Image URL');
        Resized::setDefaultImage('http:/www.example.com/no-image.jpg');
    }

    public function testInvalidHost(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        $this->expectException('InvalidArgumentException', 'Invalid Host URL');
        Resized::setHost('https:/img.resized.co');
    }

    /**
     * @throws JsonException
     */
    public function testValidHost(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://different.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '100', '100', 'A nice title');

        $this->assertEquals('https://different.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjJjZDZjZjNkNjk2MDc0YjUyZmRjYWZmZWUwMjY5YmIxMTA0OTZjY2QifQ==/a-nice-title.jpg', $img);
    }

    /**
     * @throws JsonException
     */
    public function testEmptyURL(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('', '100', '100', 'A nice title');

        $this->assertEquals('https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL25vLWltYWdlLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjMxOWI2MzM1Zjc2Njg3NmQ1N2U4NjhjZTg0NGQwN2Y4ZThlZTQwZDkifQ==/a-nice-title.jpg', $img);
    }

    /**
     * @throws JsonException
     */
    public function testInvalidURL(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http:/www.example.com/some-image-to-resize.jpg', '100', '100', 'A nice title');

        $this->assertEquals('https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL25vLWltYWdlLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjMxOWI2MzM1Zjc2Njg3NmQ1N2U4NjhjZTg0NGQwN2Y4ZThlZTQwZDkifQ==/a-nice-title.jpg', $img);
    }

    /**
     * @throws JsonException
     */
    public function testWithTitle(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        Resized::setHost('https://img.resized.co');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '100', '100', 'A nice title');

        $this->assertEquals('https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjJjZDZjZjNkNjk2MDc0YjUyZmRjYWZmZWUwMjY5YmIxMTA0OTZjY2QifQ==/a-nice-title.jpg', $img);
    }

    /**
     * @throws JsonException
     */
    public function testWithNoTitle(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '100', '100');

        $this->assertEquals('https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjJjZDZjZjNkNjk2MDc0YjUyZmRjYWZmZWUwMjY5YmIxMTA0OTZjY2QifQ==/some-image-to-resize.jpg', $img);
    }

    /**
     * @throws JsonException
     */
    public function testConstrainWidth(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '100');

        $this->assertEquals('https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6bnVsbCxcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6ImZlNjY3MjEyNGE1M2Y4MWVkY2JhNzA3OTk5MjFlMDYxN2QxYWVlZTQifQ==/some-image-to-resize.jpg', $img);
    }

    /**
     * @throws JsonException
     */
    public function testConstrainHeight()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '', '100');

        $this->assertEquals('https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIlwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjVhMWJmOTdjNDY1ZmU5YzEwNWIwMWZlODg1ZWIxNjM2MjRiMjZmZDAifQ==/some-image-to-resize.jpg', $img);
    }

    /**
     * @throws JsonException
     */
    public function testEmptyConstraintParams(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg');

        $this->assertEquals('https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpudWxsLFwiaGVpZ2h0XCI6bnVsbCxcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjgyMzAzZDljNmVmN2E3MmIyYjliMzZmNjA5MjBlODZiMzI0YzFhNjYifQ==/some-image-to-resize.jpg', $img);
    }

    /**
     * @throws JsonException
     */
    public function testNoConstraintParams(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg');

        $this->assertEquals('https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpudWxsLFwiaGVpZ2h0XCI6bnVsbCxcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjgyMzAzZDljNmVmN2E3MmIyYjliMzZmNjA5MjBlODZiMzI0YzFhNjYifQ==/some-image-to-resize.jpg', $img);
    }

    /**
     * @throws JsonException
     */
    public function testWithOptions(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '', '500', 'A nice image name', ['quality' => 100]);

        $this->assertEquals('https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIlwiLFwiaGVpZ2h0XCI6XCI1MDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6e1wicXVhbGl0eVwiOjEwMH19IiwiaGFzaCI6IjcyNmI5ZWIxYTBmNjJmNDQwOGVkOTIyNDM3M2MxMGM1ZmU0NDAzZGIifQ==/a-nice-image-name.jpg', $img);
    }

    /**
     * @throws JsonException
     */
    public function testSetMaxSlugLength(): void
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        Resized::setMaxSlugLength(10);
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '100', '100');

        $this->assertEquals('https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjJjZDZjZjNkNjk2MDc0YjUyZmRjYWZmZWUwMjY5YmIxMTA0OTZjY2QifQ==/some-i.jpg', $img);
    }

    /**
     */
    abstract protected function setupApplication(): Application;

    /**
     * Sets up and registers the service provider for the given application.
     *
     * @param Application $app The application instance to which the service provider will be added.
     */
    private function setupServiceProvider(Application $app): void
    {
        // Create and register the provider.
        $provider = new ResizedServiceProvider($app);
        $app->register($provider);
        $provider->boot();

    }
}
