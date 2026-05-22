<?php declare(strict_types=1);

namespace Shopware\Tests\Integration\Core\Maintenance\Log\Command;

use Doctrine\DBAL\Connection;
use PHPUnit\Framework\TestCase;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Test\TestCaseBase\IntegrationTestBehaviour;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\Framework\Log\Command\LogClearCommand;
use Symfony\Component\Console\Tester\CommandTester;

/**
 * @internal
 */
#[Package('framework')]
class LogClearCommandTest extends TestCase
{
    use IntegrationTestBehaviour;

    public function testClearLogs(): void
    {
        $connection = static::getContainer()->get(Connection::class);

        $this->insertLogEntries($connection);

        $countBefore = (int) $connection->fetchOne(
            'SELECT COUNT(*) FROM log_entry'
        );

        static::assertGreaterThan(0, $countBefore);

        $commandTester = new CommandTester(static::getContainer()->get(LogClearCommand::class));
        $commandTester->execute([]);

        $countAfter = (int) $connection->fetchOne(
            'SELECT COUNT(*) FROM log_entry'
        );

        static::assertSame(0, $countAfter);
    }

    public function testClearOlderThan(): void
    {
        $connection = static::getContainer()->get(Connection::class);

        $connection->insert('log_entry', [
            'id' => Uuid::randomBytes(),
            'message' => 'old',
            'level' => 100,
            'channel' => 'phpunit',
            'created_at' => '2020-01-01 00:00:00',
        ]);

        $connection->insert('log_entry', [
            'id' => Uuid::randomBytes(),
            'message' => 'new',
            'level' => 100,
            'channel' => 'phpunit',
            'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);


        $commandTester = new CommandTester(static::getContainer()->get(LogClearCommand::class));
        $commandTester->execute([
            '--days' => 30,
        ]);

        $count = (int) $connection->fetchOne(
            'SELECT COUNT(*) FROM log_entry'
        );

        static::assertSame(1, $count);
    }

    private function insertLogEntries(Connection $connection): void
    {
        $connection->insert('log_entry', [
            'id' => random_bytes(16),
            'message' => 'test',
            'level' => 100,
            'channel' => 'phpunit',
            'created_at' => (new \DateTime())->format('Y-m-d H:i:s'),
        ]);
    }
}
