<?php

declare(strict_types=1);

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Webmozart\Assert\Assert as Webmozart;

class AppInstallCommand extends Command
{
    protected static $defaultName = 'app:install';

    public function __construct(private EntityManagerInterface $em)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setDescription('Initialiases database if not yet, otherwise - run migrations');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $application = $this->getApplication();
        Webmozart::notNull($application);

        $command = $application->find('doctrine:database:create');
        $createInput = new ArrayInput(['--if-not-exists' => true]);
        $createInput->setInteractive(false);
        $command->run($createInput, $output);

        $qb = $this->em->getConnection()->createQueryBuilder();
        $stmt = $qb->select('COUNT(*)')
            ->from('INFORMATION_SCHEMA.TABLES')
            ->where('TABLE_SCHEMA = :dbName')
            ->setParameter('dbName', '%env(resolve:DATABASE_NAME)%')
            ->executeQuery();

        $isAppAlreadyInstalled = $stmt->fetchOne() > 0;

        if ($isAppAlreadyInstalled) {
            $migrateCommand = $application->find('doctrine:migrations:migrate');
            $migrateInput = new ArrayInput(['--force' => true]);
            $migrateInput->setInteractive(false);
            $migrateCommand->run($migrateInput, $output);

            return Command::SUCCESS;
        }

        $updateCommand = $application->find('doctrine:schema:update');
        $updateInput = new ArrayInput(['--force' => true]);
        $updateInput->setInteractive(false);
        $updateCommand->run($updateInput, $output);

        return Command::SUCCESS;
    }
}
