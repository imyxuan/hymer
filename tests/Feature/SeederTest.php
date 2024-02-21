<?php

namespace IMyxuan\Hymer\Tests\Feature;

use IMyxuan\Hymer\Tests\TestCase;

class SeederTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->install();
    }

    /**
     * Test manually seeding is working.
     */
    public function testHymerDatabaseSeederCanBeCalled()
    {
        $exception = null;

        try {
            $this->artisan('db:seed', ['--class' => 'HymerDatabaseSeeder']);
        } catch (\Exception $exception) {
        }

        $this->assertNull($exception, 'An exception was thrown');
    }
}
