<?php

declare(strict_types=1);

namespace Tests\Jeedom\Controller;

use GuzzleHttp\Psr7\ServerRequest;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

final class MainControllerTest extends TestCase
{
    protected function setUp(): void
    {
        $this->controller = new TestableMainController(dirname(__DIR__, 3));
    }

    public function testRedirectToInstall(): void
    {
        $request = new ServerRequest('GET', '/');
        $this->controller->jeedomInstalled = false;

        $response = ($this->controller)($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertContains('install/setup.php', $response->getHeader('Location'));
    }

    /**
     * @dataProvider provideRedirections
     */
    public function testRedirectHomeWithMediaParameter(ServerRequestInterface $request, string $location): void
    {
        $this->controller->jeedomInstalled = true;
        $this->controller->headersSent = false;

        $response = ($this->controller)($request);

        $this->assertEquals(302, $response->getStatusCode());
        $this->assertContains($location, $response->getHeader('Location'));
    }

    /**
     * @dataProvider provideRedirections
     */
    public function testSendHTMLRedirectionWhenHeadersAlreadySent(ServerRequestInterface $request, $location): void
    {
        $this->controller->jeedomInstalled = true;
        $this->controller->headersSent = true;

        $response = ($this->controller)($request);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertContains(sprintf('<script type="text/javascript">window.location.href=\'%s\';</script>', $location), $response->getBody()->getContents());
    }

    /**
     * @return array{ServerRequest, string}[]
     */
    public function provideRedirections(): iterable
    {
        yield [
            new ServerRequest('GET', '/'),
            'index.php?v=d',
        ];

        yield [
            (new ServerRequest('GET', '/'))->withQueryParams([
                'foo' => 'bar',
            ]),
            'index.php?v=d&foo=bar',
        ];

        yield [
            (new ServerRequest('GET', '/', [], null, '1.1', [
               'HTTP_USER_AGENT' => 'Android',
            ])),
            'index.php?v=m',
        ];
    }
}
