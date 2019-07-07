<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContentTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGuestCannotGetPages()
    {
        $this->get('/backend/content/pages')
            ->assertDontSee('Pages');
    }
}
