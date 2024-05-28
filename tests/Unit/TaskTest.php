<?php

namespace Tests\Unit;

use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_list_tasks()
    {
        Task::factory()->count(3)->create();

        $response = $this->getJson('/api/tasks');

        $response->assertStatus(200)->assertJsonCount(3);
    }

    public function test_can_create_task()
    {
        $user = User::factory()->create();

        $this->actingAs($user, 'api');

        $taskData = [
            'title' => 'Test Task',
            'description' => 'Test Description',
            'status' => 'pending',
            'due_date' => '2023-12-31',
        ];

        $response = $this->postJson('/api/tasks', $taskData);

        $response->assertStatus(201)->assertJsonFragment($taskData);

        $this->assertDatabaseHas('tasks', [
            'title' => 'Test Task'
        ]);
    }
}
