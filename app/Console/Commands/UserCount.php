<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

/** Usado por docker/start.sh para saber si hay que sembrar datos de demostración. */
class UserCount extends Command
{
    protected $signature = 'db:table-count';

    protected $description = 'Muestra cuántos usuarios hay registrados';

    public function handle(): int
    {
        $this->line((string) User::count());

        return self::SUCCESS;
    }
}
