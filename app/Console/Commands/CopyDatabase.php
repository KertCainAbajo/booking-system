<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * One-time move of all data from this database (MySQL) into another one (e.g. Supabase).
 *
 *   php artisan migrate --database=supabase --force
 *   php artisan db:copy-to supabase
 */
class CopyDatabase extends Command
{
    protected $signature = 'db:copy-to {target : Connection name in config/database.php, e.g. supabase}
                            {--force : Copy even if the target already has data (existing rows are replaced)}';

    protected $description = 'Copy every row from the default database into another database that already has the same tables';

    /** Temporary / rebuildable tables that aren't worth moving. */
    protected array $skip = ['migrations', 'cache', 'cache_locks', 'sessions', 'jobs', 'job_batches', 'failed_jobs', 'password_reset_tokens',
        'pulse_aggregates', 'pulse_entries', 'pulse_values'];

    public function handle(): int
    {
        $source = DB::connection();
        $target = DB::connection($this->argument('target'));

        $this->info("From: {$source->getDriverName()} / {$source->getDatabaseName()}");
        $this->info("To:   {$target->getDriverName()} / {$target->getDatabaseName()}");

        $tables = $this->tablesInDependencyOrder($target->getName());
        $tables = array_values(array_filter($tables, fn ($t) => ! in_array($t, $this->skip, true)
            && Schema::connection($source->getName())->hasTable($t)));

        if (! $this->option('force')) {
            $notEmpty = array_filter($tables, fn ($t) => $target->table($t)->exists());
            if ($notEmpty) {
                $this->error('The target already has data in: ' . implode(', ', $notEmpty) . '. Use --force to replace it.');

                return self::FAILURE;
            }
        }

        $target->transaction(function () use ($source, $target, $tables) {
            // Empty children before parents, then fill parents before children
            foreach (array_reverse($tables) as $table) {
                $target->table($table)->delete();
            }

            foreach ($tables as $table) {
                $targetColumns = Schema::connection($target->getName())->getColumnListing($table);
                $total = 0;
                $source->table($table)->orderBy($this->orderColumn($source->getName(), $table))
                    ->chunk(500, function ($rows) use ($target, $table, $targetColumns, &$total) {
                        $data = $rows->map(fn ($row) => array_intersect_key((array) $row, array_flip($targetColumns)))->all();
                        $target->table($table)->insert($data);
                        $total += count($data);
                    });
                $this->line(sprintf('  %-28s %6d rows', $table, $total));
            }
        });

        // PostgreSQL keeps its own id counters; move them past the copied ids
        if ($target->getDriverName() === 'pgsql') {
            foreach ($tables as $table) {
                if (in_array('id', Schema::connection($target->getName())->getColumnListing($table), true)) {
                    $target->statement("SELECT setval(pg_get_serial_sequence('\"{$table}\"', 'id'), COALESCE((SELECT MAX(id) FROM \"{$table}\"), 0) + 1, false)
                        WHERE pg_get_serial_sequence('\"{$table}\"', 'id') IS NOT NULL");
                }
            }
        }

        $this->info('Done. Every table was copied.');

        return self::SUCCESS;
    }

    /** Parent tables first, so foreign keys always point at rows that already exist. */
    protected function tablesInDependencyOrder(string $connection): array
    {
        $schema = Schema::connection($connection);
        $tables = array_map(fn ($t) => $t['name'], $schema->getTables());
        $deps = [];
        foreach ($tables as $table) {
            $deps[$table] = array_values(array_filter(
                array_map(fn ($fk) => $fk['foreign_table'], $schema->getForeignKeys($table)),
                fn ($parent) => $parent !== $table && in_array($parent, $tables, true)
            ));
        }

        $ordered = [];
        $visit = function ($table, $path = []) use (&$visit, &$ordered, $deps) {
            if (in_array($table, $ordered, true) || in_array($table, $path, true)) {
                return;
            }
            foreach ($deps[$table] as $parent) {
                $visit($parent, [...$path, $table]);
            }
            $ordered[] = $table;
        };
        foreach ($tables as $table) {
            $visit($table);
        }

        return $ordered;
    }

    protected function orderColumn(string $connection, string $table): string
    {
        $columns = Schema::connection($connection)->getColumnListing($table);

        return in_array('id', $columns, true) ? 'id' : $columns[0];
    }
}
