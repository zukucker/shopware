<?php declare(strict_types=1);

namespace Shopware\Tests\Integration\Core\Maintenance\Log;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Log\LogCleanupService;


class LogCLeanupServiceTest extends TestCase
{
    public function testClear(): void
    {
        $connection = $this->createMock(Connection::class);

        $connection
            ->expects(static::once())
            ->method('executeStatement')
            ->with('DELETE FROM log_entry')
            ->willReturn(5);

        $service = new LogCleanupService($connection);

        static::assertSame(5, $service->clear());
    }

    public function testClearOlderThan(): void
    {
        $connection = $this->createMock(Connection::class);

        $connection
            ->expects(static::once())
            ->method('executeStatement')
            ->with(
                'DELETE FROM log_entry WHERE created_at < :date',
                static::callback(function (array $params): bool {
                    return isset($params['date']);
                })
            )
            ->willReturn(10);

        $service = new LogCleanupService($connection);

        static::assertSame(10, $service->clearOlderThan(30));
    }
}
