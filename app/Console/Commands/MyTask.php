<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ResetReportes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mytask:run';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resetea reportes de todos los usuarios con rol Ciudadano a 0 el dia 1 de cada mes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        DB::statement('UPDATE users SET cantidad_reportes = 0 WHERE rol = "Ciudadano";');
    }
}