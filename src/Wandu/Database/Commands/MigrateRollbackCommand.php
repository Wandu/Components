<?php
namespace Wandu\Database\Commands;

use Wandu\Console\Command;
use Wandu\Database\Migrator\Migrator;

class MigrateRollbackCommand extends Command
{
    /** @var string */
    protected $description = 'Run rollback.';

    /** @var array */
    protected $arguments = [
        'until?' => 'the migrate id for the rollback',
    ];

    /** @var \Wandu\Database\Migrator\Migrator */
    protected $manager;

    /**
     * @param \Wandu\Database\Migrator\Migrator $manager
     */
    public function __construct(Migrator $manager)
    {
        $this->manager = $manager;
    }
    
    public function execute()
    {
        $untilMigrationId = $this->input->getArgument('until');
        
        /** @var \Wandu\Database\Migrator\MigrationContainer[] $migrations */
        $migrations = array_reverse($this->manager->migrations());

        if (!count($migrations)) {
            $this->output->writeln("<comment>there is no migration to rollback.</comment>");
            return 2;
        }
        foreach ($migrations as $migration) {
            if (!$migration->isApplied()) {
                continue;
            }
            $this->manager->down($migration->getId());
            $this->output->writeln(sprintf("<info>down</info> %s", $migration->getId()));
            if ($migration->getId() === $untilMigrationId || !$untilMigrationId) {
                break;
            }
        }
    }
}
