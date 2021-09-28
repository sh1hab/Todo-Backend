<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Illuminate\Support\Str;

class UserTokenTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCanCreateToken()
    {
        $uuid = Str::orderedUuid();

        $response = $this->post('api/v1/generate/user/token', [
            'uuid' => $uuid,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseCount('user_tokens', 1);
    }
}
