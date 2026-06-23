<?php declare(strict_types=1);

namespace Shopware\Core\Migration\V6_7;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Log\Package;
use Shopware\Core\Framework\Migration\MigrationStep;

/**
 * @internal
 */
#[Package('framework')]
class Migration1782202756AddDocumentMail extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1782202756;
    }

    public function update(Connection $connection): void
    {
        $this->addColumn(
            connection: $connection,
            table: 'customer',
            column: 'document_mail',
            type: 'VARCHAR(255)'
        );

    }
}
