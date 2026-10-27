<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

/**
 * Creates this app's PostgreSQL schema (DB_SCHEMA, e.g. "dexter") if it doesn't exist yet.
 * Runs before migrations, because Laravel's migrations table is created inside that schema.
 */
class EnsureDatabaseSchema extends Command
{
    protected $signature = 'db:ensure-schema {--database= : Connection name (default: the app\'s connection)}';

    protected $description = 'Create the PostgreSQL schema this app uses, if it is missing';

    public function handle(): int
    {
        $connection = DB::connection($this->option('database'));

        if ($connection->getDriverName() !== 'pgsql') {
            return self::SUCCESS;
        }

        $schema = (string) $connection->getConfig('search_path');
        if ($schema === '' || $schema === 'public') {
            return self::SUCCESS;
        }

        if (! preg_match('/^[a-z_][a-z0-9_]*$/', $schema)) {
            $this->error("Invalid schema name: {$schema}");

            return self::FAILURE;
        }

        $connection->statement("CREATE SCHEMA IF NOT EXISTS \"{$schema}\"");
        $this->info("Schema \"{$schema}\" is ready.");

        return self::SUCCESS;
    }
}
