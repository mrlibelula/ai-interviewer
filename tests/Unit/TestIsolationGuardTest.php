<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TestIsolationGuardTest extends TestCase
{
  public function test_uses_in_memory_sqlite_only(): void
  {
    $this->assertSame('sqlite', config('database.default'));
    $this->assertSame(':memory:', config('database.connections.sqlite.database'));
  }

  public function test_storage_disks_are_faked(): void
  {
    Storage::disk('local')->put('isolation-check.txt', 'ok');
    Storage::disk('public')->put('isolation-check.txt', 'ok');

    $this->assertTrue(Storage::disk('local')->exists('isolation-check.txt'));
    $this->assertTrue(Storage::disk('public')->exists('isolation-check.txt'));
    $this->assertFileDoesNotExist(storage_path('app/isolation-check.txt'));
    $this->assertFileDoesNotExist(storage_path('app/public/isolation-check.txt'));
  }
}
