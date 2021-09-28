<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Todo;

class TodoTest extends TestCase
{
    use DatabaseMigrations, withFaker;

    /**
     * 
     *
     * @return void
     */
    public function testCanFetchTodos()
    {
        $random_number = mt_rand(10, 100);
        $todo = Todo::factory()->count($random_number)->create();

        $response = $this->withCookie('uuid', $todo->first()->user_token->uuid)->get('api/v1/todos');

        $response->assertStatus(200);

        $this->assertDatabaseCount('todos', $random_number);
    }

    /**
     * 
     *
     * @return void
     */
    public function testCanClearTodos()
    {
        $todo = Todo::factory()->create();

        $response = $this->withCookie('uuid', $todo->first()->user_token->uuid)->delete("api/v1/todos/clear");

        $response->assertStatus(200);

        $this->assertDatabaseCount('todos', 1);
    }
}
