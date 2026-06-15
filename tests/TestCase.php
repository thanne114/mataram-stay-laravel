<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Register custom SQLite math functions if testing database is SQLite
        $connection = config('database.default');
        if ($connection === 'sqlite' || config("database.connections.{$connection}.driver") === 'sqlite') {
            try {
                $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
                $pdo->sqliteCreateFunction('acos', 'acos', 1);
                $pdo->sqliteCreateFunction('cos', 'cos', 1);
                $pdo->sqliteCreateFunction('sin', 'sin', 1);
                $pdo->sqliteCreateFunction('radians', 'deg2rad', 1);
            } catch (\Exception $e) {
                // Fail silently if DB is not initialized yet
            }
        }
    }
}
