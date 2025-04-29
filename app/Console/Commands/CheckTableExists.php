<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;

class CheckTableExists extends Command
{
    protected $signature = 'db:table {--exists=}';
    protected $description = 'Check if a database table exists';

    public function handle(): int
    {
        $table = $this->option('exists');

        if (! $table) {
            $this->error('No table specified.');
            return 1;
        }

        if (! Schema::hasTable($table)) {
            $this->warn("Table '{$table}' does not exist yet.");
            return 1;
        }

        $this->info("Table '{$table}' exists.");
        return 0;
    }
}
