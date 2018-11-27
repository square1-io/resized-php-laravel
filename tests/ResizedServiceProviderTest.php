<?php

namespace Square1\Laravel\Resized\Test;

use Illuminate\Container\Container;
use Square1\Laravel\Resized\ResizedServiceProvider;
use Square1\Laravel\Resized\ResizedFacade as Resized;
use PHPUnit\Framework\TestCase;

abstract class ResizedServiceProviderTest extends TestCase
{
    public function testRegisterResizedServiceProvider()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);

        $resized = $app['resized'];
        $this->assertInstanceOf('Square1\Resized\Resized', $resized);
    }

    public function testConfigData()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        $config = $app['config']->get('resized');

        $this->assertArrayHasKey('host', $config);
        $this->assertArrayHasKey('key', $config);
        $this->assertArrayHasKey('secret', $config);
        $this->assertArrayHasKey('default', $config);
    }

    public function testInvalidDefaultURL()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        $this->expectException(\InvalidArgumentException::class, 'Invalid Default Image URL');
        Resized::setDefaultImage('http:/www.example.com/no-image.jpg');
    }

    public function testInvalidHost()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        $this->expectException('InvalidArgumentException', 'Invalid Host URL');
        Resized::setHost('https:/img.resized.co');
    }

    public function testValidHost()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://different.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '100', '100', 'A nice title');

        $this->assertEquals($img, 'https://different.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjJjZDZjZjNkNjk2MDc0YjUyZmRjYWZmZWUwMjY5YmIxMTA0OTZjY2QifQ==/a-nice-title.jpg');
    }

    public function testEmptyURL()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('', '100', '100', 'A nice title');

        $this->assertEquals($img, 'https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL25vLWltYWdlLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjMxOWI2MzM1Zjc2Njg3NmQ1N2U4NjhjZTg0NGQwN2Y4ZThlZTQwZDkifQ==/a-nice-title.jpg');
    }

    public function testInvalidURL()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http:/www.example.com/some-image-to-resize.jpg', '100', '100', 'A nice title');

        $this->assertEquals($img, 'https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL25vLWltYWdlLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjMxOWI2MzM1Zjc2Njg3NmQ1N2U4NjhjZTg0NGQwN2Y4ZThlZTQwZDkifQ==/a-nice-title.jpg');
    }

    public function testWithTitle()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        Resized::setHost('https://img.resized.co');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '100', '100', 'A nice title');

        $this->assertEquals($img, 'https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjJjZDZjZjNkNjk2MDc0YjUyZmRjYWZmZWUwMjY5YmIxMTA0OTZjY2QifQ==/a-nice-title.jpg');
    }

    public function testWithNoTitle()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '100', '100');

        $this->assertEquals($img, 'https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjJjZDZjZjNkNjk2MDc0YjUyZmRjYWZmZWUwMjY5YmIxMTA0OTZjY2QifQ==/some-image-to-resize.jpg');
    }

    public function testConstrainWidth()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '100');

        $this->assertEquals($img, 'https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIjEwMFwiLFwiaGVpZ2h0XCI6XCJcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjM2YjEzNjljNmIyM2RhZmM4Y2VkZTQ1MTJiYzk5NTdlYWRjMDc1ZWMifQ==/some-image-to-resize.jpg');
    }

    public function testConstrainHeight()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '', '100');

        $this->assertEquals($img, 'https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIlwiLFwiaGVpZ2h0XCI6XCIxMDBcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjVhMWJmOTdjNDY1ZmU5YzEwNWIwMWZlODg1ZWIxNjM2MjRiMjZmZDAifQ==/some-image-to-resize.jpg');
    }

    public function testEmptyConstraintParams()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg', '', '');

        $this->assertEquals($img, 'https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIlwiLFwiaGVpZ2h0XCI6XCJcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjMzMGZhODdhOWFmNGJmNTZiOWI2ODQ5NjAxNTZmMmYwNWRiY2Y0ZTUifQ==/some-image-to-resize.jpg');
    }

    public function testNoConstraintParams()
    {
        $app = $this->setupApplication();
        $this->setupServiceProvider($app);
        Resized::setFacadeApplication($app);

        Resized::setHost('https://img.resized.co');
        Resized::setDefaultImage('http://www.example.com/no-image.jpg');
        $img = Resized::process('http://www.example.com/some-image-to-resize.jpg');

        $this->assertEquals($img, 'https://img.resized.co/key/eyJkYXRhIjoie1widXJsXCI6XCJodHRwOlxcXC9cXFwvd3d3LmV4YW1wbGUuY29tXFxcL3NvbWUtaW1hZ2UtdG8tcmVzaXplLmpwZ1wiLFwid2lkdGhcIjpcIlwiLFwiaGVpZ2h0XCI6XCJcIixcImRlZmF1bHRcIjpcImh0dHA6XFxcL1xcXC93d3cuZXhhbXBsZS5jb21cXFwvbm8taW1hZ2UuanBnXCIsXCJvcHRpb25zXCI6W119IiwiaGFzaCI6IjMzMGZhODdhOWFmNGJmNTZiOWI2ODQ5NjAxNTZmMmYwNWRiY2Y0ZTUifQ==/some-image-to-resize.jpg');
    }

    /**
     * @return Container
     */
    abstract protected function setupApplication();

    /**
     * @param Container $app
     *
     * @return AwsServiceProvider
     */
    private function setupServiceProvider(Container $app)
    {
        // Create and register the provider.
        $provider = new ResizedServiceProvider($app);
        $app->register($provider);
        $provider->boot();

        return $provider;
    }
}
