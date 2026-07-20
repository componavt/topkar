<?php
namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class TopkarDatabase
{
    public function use(): void
    {
        Config::set('database.connections.mysql.database', 'topkar');

        DB::purge('mysql');
        DB::reconnect('mysql');
    }
}