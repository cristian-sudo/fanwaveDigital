<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\Bundle\DoctrineBundle\Command\Proxy\UpdateSchemaDoctrineCommand as DoctrineUpdateSchemaDoctrineCommand;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Finder\Finder;
use Webmozart\Assert\Assert as WebmozartAssert;

/**
 * This command is used in development mode to automatically
 * populate `doctrine_migration_versions` table with all existing migrations
 * in `migrations` when `doctrine:schema:update --force` is used.
 */
class UpdateSchemaDoctrineCommand extends DoctrineUpdateSchemaDoctrineCommand
{
    private const MIGRATIONS_TABLE_NAME = 'doctrine_migration_versions';

    private string $migrationsDir;

    public function __construct(string $migrationsDir)
    {
        $this->migrationsDir = $migrationsDir;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        parent::execute($input, $output);

        $finder = new Finder();
        $migrations = $finder->files()->in($this->migrationsDir);

        $force = (bool) $input->getOption('force');

        if ($force && $migrations->count() > 0) {
            $application = $this->getApplication();
            WebmozartAssert::notNull($application);

            $syncMetadataStorageCommand = $application->find('doctrine:migrations:sync-metadata-storage');
            $syncMetadataStorageCommand->run(new ArgvInput([]), $output);

            $addVersionsCommand = $application->find('doctrine:migrations:version');
            $addVersionsInput = new ArrayInput(['--add' => true, '--all' => true, '--no-interaction' => true]);
            $addVersionsInput->setInteractive(false);
            $addVersionsCommand->run($addVersionsInput, $output);

            $ui = new SymfonyStyle($input, $output);
            $ui->note(sprintf('Added %d migrations to %s', $migrations->count(), self::MIGRATIONS_TABLE_NAME));
        }

        return 0;
    }
}
