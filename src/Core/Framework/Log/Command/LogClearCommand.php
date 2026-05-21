<?php declare(strict_types=1);

namespace Shopware\Core\Framework\Log\Command;

use Shopware\Core\Framework\Adapter\Console\ShopwareStyle;
use Shopware\Core\Framework\Log\LogCleanupService;
use Shopware\Core\Framework\Log\Package;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
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

    protected function configure(): void
    {
        $this->addOption('days', 'd', InputOption::VALUE_REQUIRED, 'Clear logs older than x days');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new ShopwareStyle($input, $output);
        $days = intval($input->getOption('days'));
        try{
            if($days){
                $io->comment(\sprintf('Clearing all logs older than %s days', $days));
                $this->logCleanupService->clearOlderThan($days);
                $io->success(\sprintf('Cleared all logs older than %s days', $days));
            }else{
                $io->comment(\sprintf('Clearing all logs'));
                $this->logCleanupService->clear();
                $io->success(\sprintf('Cleared all logs'));
            }
            return self::SUCCESS;
        }catch(\Throwable $e){
            return self::FAILURE;
        }
    }
}
