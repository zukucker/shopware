<?php declare(strict_types=1);

namespace Shopware\Core\Installer\Tests\Controller;

use Shopware\Core\Installer\Controller\FinishController;
use Shopware\Core\Installer\Finish\SystemLocker;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Request;

class FinishControllerTest extends TestCase
{
    private SystemLocker $systemLocker;
    private Client $client;
    private string $appUrl;

    protected function setUp(): void
    {
        $this->systemLocker = $this->createMock(SystemLocker::class);
        $this->client = $this->createMock(Client::class);
        $this->appUrl = 'http://localhost';
    }

    public function testFinishWithCompletionParameterReturnsTemplate(): void
    {
        $controller = new FinishController(
            $this->systemLocker,
            $this->client,
            $this->appUrl
        );

        $request = new Request(['completed' => '1']);
        $request->setSession(new Session(new MockArraySessionStorage()));
        $response = $controller->finish($request);
        static::assertInstanceOf(Response::class, $response);
        static::assertSame(200, $response->getStatusCode());
    }
}
