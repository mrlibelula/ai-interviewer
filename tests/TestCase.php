<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Storage;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        $this->assertTestsCannotTouchRealDatabase();
        $this->fakeApplicationStorageDisks();
    }

    /**
     * Hard-fail if a test is somehow pointed at the real SQLite file / non-memory DB.
     */
    protected function assertTestsCannotTouchRealDatabase(): void
    {
        $connection = (string) config('database.default');
        $database = (string) config("database.connections.{$connection}.database");

        $isMemorySqlite = $connection === 'sqlite' && ($database === ':memory:' || $database === '');
        $looksLikeRealSqliteFile = str_contains(str_replace('\\', '/', $database), '/storage/database/')
            || str_ends_with($database, 'database.sqlite');

        if (!$isMemorySqlite || $looksLikeRealSqliteFile) {
            $this->fail(
                "Tests must use in-memory sqlite only. Refusing to run against [{$connection}] database [{$database}]."
            );
        }
    }

    /**
     * Keep tests off real storage/app files.
     */
    protected function fakeApplicationStorageDisks(): void
    {
        Storage::fake('local');
        Storage::fake('public');
    }
}
