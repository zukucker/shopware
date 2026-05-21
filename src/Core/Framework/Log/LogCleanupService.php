<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Log;

use Doctrine\DBAL\Connection;

class LogCleanupService
{
    public function __construct(
        private readonly Connection $connection
    ) {
    }

    public function clear(): void
    {
        $this->connection->executeStatement(
            'DELETE FROM log_entry'
        );
    }

    public function clearOlderThan(int $days): int
    {
        return $this->connection->executeStatement(
            'DELETE FROM log_entry WHERE created_at < DATE_SUB(NOW(), INTERVAL :days DAY)',
            ['days' => $days]
        );
    }
}
