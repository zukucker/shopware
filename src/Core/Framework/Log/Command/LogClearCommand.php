<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Log\Command;

use Shopware\Core\Framework\Log\LogCleanupService;
use Shopware\Core\Framework\Log\Package;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;

/**
 * @phpstan-import-type PluginInfo from KernelPluginLoader
 */
#[AsCommand(
    name: 'system:log:clear',
    description: 'Clears all logs',
)]
#[Package('framework')]
class LogClearCommand extends Command
{
    public function __construct(
        private readonly string $projectDir,
        private readonly LogCleanupService $logCleanupService
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->logCleanupService->clear();
        return 0;
    }
}
