<?php

/** @noinspection PhpUndefinedClassInspection */

declare(strict_types = 1);

use Robo\Collection\CollectionBuilder;
use Robo\Tasks;

class RoboFile extends Tasks
{
    public function qaAll(): void
    {
        $this->qaCs();
        $this->qaPhpstan();
    }

    public function qaCs($opts = ['dry-run' => false]): int
    {
        $task = $this->taskExec('vendor/bin/php-cs-fixer fix');
        $task->arg('--diff');

        if ($opts['dry-run']) {
            $task->arg('--dry-run');
        }

        return $this->runAndReturnExitCode($task);
    }

    public function qaPhpstan(): int
    {
        $task = $this->taskExec('vendor/bin/phpstan analyse src --memory-limit=1G --level=max');

        return $this->runAndReturnExitCode($task);
    }

    public function dbDrop(): void
    {
        $this->_exec('bin/console doctrine:database:drop --force');
    }

    public function dbCreate(): void
    {
        $this->_exec('bin/console doctrine:database:create');
    }

    public function dbMigrate(): void
    {
        $this->_exec('bin/console doctrine:migrations:migrate --no-interaction');
    }

    public function dbMigrateNext(): void
    {
        $this->_exec('bin/console doctrine:migrations:migrate next');
    }

    public function dbMigratePrev(): void
    {
        $this->_exec('bin/console doctrine:migrations:migrate prev');
    }

    public function dbFixtures(): void
    {
        $this->_exec('bin/console doctrine:fixtures:load --no-interaction');
    }

    public function dbUp(): void
    {
        $this->dbMigrate();
        $this->dbFixtures();
    }

    public function dbReset(): void
    {
        $this->dbDrop();
        $this->dbCreate();

        $this->dbMigrate();
        $this->dbFixtures();
    }

    public function testUnit(): void
    {
        $this->_exec('bin/phpunit --testsuite Unit');
    }

    public function cacheClean(string $env = 'test'): void
    {
        $this->_exec("bin/console cache:clear --no-warmup --env=${env}");
    }

    private function runAndReturnExitCode(CollectionBuilder $task): int
    {
        $result = $task->run();

        return $result->getExitCode();
    }
}
